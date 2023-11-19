<?php

namespace Model;

use Blink\Model;
use User;

/**
 * GameMatch model
 * 
 * @extends		Model<GameMatch>
 */
class GameMatch extends Model {
	public static String $table = "matches";

	public static $fillables = Array(
		"id",
		"user" => "user_id",
		"heartbeat",
		"active"
	);

	public int $id;

	public User $user1;

	public int $heartbeat;

	public bool $active;
}
