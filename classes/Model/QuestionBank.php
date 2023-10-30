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
		"icon",
		"questions",
		"maxAttempts" => "max_attempts"
	);

	public int $id;

	public String $name;

	public String $icon = null;

	public int $questions;

	public int $maxAttempts;
}
