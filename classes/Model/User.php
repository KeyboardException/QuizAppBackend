<?php

namespace Model;

use Blink\Attribute\SensitiveField;
use Blink\Model;

/**
 * User model
 * 
 * @extends	Model<User>
 */
class User extends Model {

	public static String $table = "users";

	public static $fillables = Array(
		"id",
		"username",
		"name",
		"email",
		"password",
		"score",
		"created"
	);

	public int $id;

	public String $username;

	public String $name;

	public String $email;

	#[SensitiveField]
	public String $password;

	public float $score = 0;

	public int $created;

	public static function getByUsername(String $username): User|null {
		return static::where("username", $username) -> first();
	}
}