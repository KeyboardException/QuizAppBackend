<?php

namespace Controller;

use Blink\Request;
use Blink\Response\APIResponse;
use Blink\Session;

class User {
	public static function get(Request $request, int $id) {
		$user = \User::getByID($id);
		return new APIResponse(0, "Success!", 200, $user);
	}

	public static function ranking() {
		Session::requireLogin();

		$users = \User::query()
			-> sort("score", "DESC")
			-> all(0, 50);

		return new APIResponse(0, "Success!", 200, Array(
			"ranking" => $users,
			"me" => Session::$user
		));
	}
}
