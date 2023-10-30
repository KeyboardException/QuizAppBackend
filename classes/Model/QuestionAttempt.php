<?php

namespace Model;

use Blink\Attribute\Lazyload;
use Blink\Model;

/**
 * QuestionAttempt model
 * 
 * @extends		Model<QuestionAttempt>
 * @property	Attempt		$attempt
 * @property	Question	$question
 */
class QuestionAttempt extends Model {
	
	const WAITING = "waiting";
	
	const ANSWERED = "answered";

	public static String $table = "question_attempts";

	public static $fillables = Array(
		"id",
		"attempt" => "attempt_id",
		"question" => "question_id",
		"status",
		"answered",
		"correct"
	);

	public int $id;

	#[Lazyload("attempt")]
	protected Attempt $_attempt;

	#[Lazyload("question")]
	protected Question $_question;

	public String $status = QuestionAttempt::WAITING;

	public ?int $answered = null;

	public bool $correct = false;

	protected function saveField($name) {
		switch ($name) {
			case "attempt":
				return $this -> attempt -> id;

			case "question":
				return $this -> question -> id;
			
			default:
				return parent::saveField($name);
		}
	}

	protected static function processField(String $name, $value) {
		switch ($name) {
			case "attempt":
				return Attempt::getByID($value, Model::MUST_EXIST);

			case "question":
				return Question::getByID($value, Model::MUST_EXIST);
			
			default:
				return parent::processField($name, $value);
		}
	}
}
