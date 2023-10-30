<?php

namespace Model;

use Blink\Attribute\Lazyload;
use Blink\Exception\InvalidValue;
use Blink\Model;
use Blink\Response\APIResponse;
use Model\Question;
use UnexpectedValueException;
use User;

/**
 * Attempt model
 * 
 * @extends		Model<Attempt>
 * 
 * @property	User			$user
 * @property	QuestionBank	$bank
 */
class Attempt extends Model {

	const INPROGRESS = "inprogress";
	
	const COMPLETED = "completed";

	public static String $table = "attempts";

	public static $fillables = Array(
		"id",
		"user" => "user_id",
		"bank" => "bank_id",
		"total",
		"status",
		"correct",
		"score",
		"created"
	);

	public int $id;

	#[Lazyload("user")]
	protected User $_user;

	#[Lazyload("bank")]
	protected QuestionBank $_bank;

	public int $total;

	public String $status = Attempt::INPROGRESS;

	public ?int $correct = null;

	public ?int $score = null;

	public int $created;

	public function answer(QuestionAttempt $questionAttempt, int $answer) {
		if ($questionAttempt -> attempt -> id !== $this -> id)
			throw new UnexpectedValueException("Invalid question attempt! It does not belong to this attempt.");
	
		$question = $questionAttempt -> question;
		$questionAttempt -> answered = $answer;
		$questionAttempt -> status = QuestionAttempt::ANSWERED;
		$questionAttempt -> correct = ($question -> answer == $answer);
		$questionAttempt -> save();

		return Array(
			"correct" => $questionAttempt -> correct,
			"state" => $this -> update()
		);
	}

	public function update() {
		$qas = $this -> getQuestionAttempts();
		$completed = 0;
		$correct = 0;
		$score = 0;

		foreach ($qas as $qa) {
			if ($qa -> status === QuestionAttempt::ANSWERED) {
				$completed += 1;
				
				if ($qa -> correct) {
					$correct += 1;
					$score += 10;
				}
			}
		}

		$this -> total = count($qas);
		$this -> correct = $correct;
		$this -> score = $score;

		if ($this -> total === $completed) {
			$this -> status = static::COMPLETED;
			$this -> user -> update();
		} else {
			// Keep this in progress.
			$this -> status = static::INPROGRESS;
		}

		$this -> save();

		return Array(
			"status" => $this -> status,
			"total" => $this -> total,
			"completed" => $completed,
			"result" => Array(
				"correct" => $this -> correct,
				"score" => $this -> score
			)
		);
	}

	public function getQuestionAttempts() {
		return QuestionAttempt::where("attempt_id", $this -> id)
			-> all();
	}

	protected function saveField($name) {
		switch ($name) {
			case "bank":
				return $this -> bank -> id;

			case "user":
				return $this -> user -> id;
			
			default:
				return parent::saveField($name);
		}
	}

	protected static function processField(String $name, $value) {
		switch ($name) {
			case "bank":
				return QuestionBank::getByID($value, Model::MUST_EXIST);

			case "user":
				return User::getByID($value);
			
			default:
				return parent::processField($name, $value);
		}
	}

	public static function start(User $user, QuestionBank $bank) {
		// Get questions that has attempted and answered correctly.
		$attempted = Question::query()
			-> leftJoin("question_attempts", "questions.id", "question_attempts.question_id")
			-> leftJoin("attempts", "attempts.id", "question_attempts.attempt_id")
			-> where("questions.bank_id", $bank -> id)
			-> where("attempts.user_id", $user -> id)
			-> where("question_attempts.correct", true)
			-> all();

		$aIds = array_map(fn ($item) => $item -> id, $attempted);

		// Get questions that has not attempted.
		$availables = Question::query()
			-> whereNot("id", $aIds)
			-> where("bank_id", $bank -> id)
			-> all();

		/** @var Question[] */
		$questions = Array();

		while (count($questions) < 5) {
			if (empty($availables)) {
				// Fallback to picking attempted question.
				$index = randBetween(0, count($attempted) - 1);
				$item = array_splice($attempted, $index, 1);
				$questions[] = reset($item);
				continue;
			}

			$index = randBetween(0, count($availables) - 1);
			$item = array_splice($availables, $index, 1);
			$questions[] = reset($item);
		}

		$attempt = static::create(Array(
			"user" => $user,
			"bank" => $bank,
			"total" => count($questions)
		)) -> save();

		$data = Array(
			"attempt" => Array(
				"id" => $attempt -> id,
				"bank" => $attempt -> bank,
				"created" => $attempt -> created
			),
			"questions" => Array()
		);

		foreach ($questions as $question) {
			/** @var Question $question */

			$questionAttempt = $question -> createAttempt($attempt);

			$data["questions"][] = Array(
				"attempt" => $questionAttempt -> id,
				"id" => $question -> id,
				"answer1" => $question -> answer1,
				"answer2" => $question -> answer2,
				"answer3" => $question -> answer3,
				"answer4" => $question -> answer4
			);
		}

		return new APIResponse(0, "Started new question attempt", 200, $data);
	}
}
