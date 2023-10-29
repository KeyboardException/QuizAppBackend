<?php

namespace Model;

use Blink\Model;

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
		"questions",
		"max_attempts"
	);

	public int $id;

	public String $name;

	public int $questions;

	public int $max_attempts;
}
