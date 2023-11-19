<?php

namespace Model;

use Blink\Model;
use User;

/**
 * GameQueue model
 * 
 * @extends		Model<GameQueue>
 */
class GameQueue extends Model {
	public static String $table = "queue";

	public static $fillables = Array(
		"id",
		"user" => "user_id",
		"heartbeat",
		"active"
	);

	public int $id;

	public User $user;

	public int $heartbeat;

	public bool $active;
}
