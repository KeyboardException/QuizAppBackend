<?php

namespace Model;

use Blink\Attribute\Lazyload;
use Blink\Model;

/**
 * Question model
 * 
 * @extends		Model<Question>
 * @property	QuestionBank		$bank
 */
class Question extends Model {

	public static String $table = "questions";

	public static $fillables = Array(
		"id",
		"bank" => "bank_id",
		"question",
		"answer1",
		"answer2",
		"answer3",
		"answer4",
		"answer"
	);

	public int $id;

	#[Lazyload("bank")]
	protected QuestionBank $_bank;

	public String $question;

	public String $answer1;

	public String $answer2;

	public String $answer3;

	public String $answer4;

	public int $answer;

	protected function saveField($name) {
		switch ($name) {
			case "bank":
				return $this -> bank -> id;
			
			default:
				return parent::saveField($name);
		}
	}

	protected function processField(String $name, $value) {
		switch ($name) {
			case "bank":
				return QuestionBank::getByID($value, Model::MUST_EXIST);
			
			default:
				return parent::processField($name, $value);
		}
	}

	public function createAttempt(Attempt $attempt) {
		return QuestionAttempt::create(Array(
			"attempt" => $attempt,
			"question" => $this,
			"answered" => null,
			"correct" => false
		)) -> save();
	}
}
