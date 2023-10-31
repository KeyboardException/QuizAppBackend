<?php

namespace Model;

use Blink\Model;
use Blink\Session;

/**
 * QuestionBank model
 * 
 * @extends	Model<QuestionBank>
 */
class QuestionBank extends Model {

	public static String $table = "question_banks";

	public static $fillables = Array(
		"id",
		"name",
		"icon",
		"questions",
		"maxAttempts" => "max_attempts"
	);

	public int $id;

	public String $name;

	public ?String $icon = null;

	public int $questions;

	public int $maxAttempts;

	public function jsonSerialize(int $depth = -1) {
		$data = parent::jsonSerialize($depth);

		if (Session::loggedIn()) {
			$user = Session::$user;

			$count = Question::query()
				-> leftJoin("question_attempts", "question_attempts.question_id", "questions.id")
				-> leftJoin("attempts", "question_attempts.attempt_id", "attempts.id")
				-> where("question_attempts.status", QuestionAttempt::ANSWERED)
				-> where("question_attempts.correct", true)
				-> where("attempts.user_id", $user -> id)
				-> where("attempts.bank_id", $this -> id)
				-> count();

			$data["completed"] = $count;
		}

		return $data;
	}
}
