<?php

namespace Model;

use Blink\Attribute\Lazyload;
use Blink\Model;
use User;

/**
 * Comment model
 * 
 * @extends		Model<Comment>
 * @property	User	$profile
 * @property	User	$author
 */
class Comment extends Model {

	public static String $table = "comments";

	public static $fillables = Array(
		"id",
		"profile" => "profileid",
		"author" => "authorid",
		"score",
		"content",
		"created"
	);

	public int $id;

	#[Lazyload("profile")]
	protected User $__profile;

	#[Lazyload("author")]
	protected User $__author;

	public float $score;

	public String $content;

	public int $created;

	protected function saveField($name) {
		switch ($name) {
			case "profile":
				return $this -> profile -> id;

			case "author":
				return $this -> author -> id;
			
			default:
				return parent::saveField($name);
		}
	}

	protected static function processField(String $name, $value) {
		switch ($name) {
			case "profile":
			case "author":
				return User::getByID($value);
			
			default:
				return parent::processField($name, $value);
		}
	}
}
