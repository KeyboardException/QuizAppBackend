<?php

namespace Controller;

use Blink\Request;
use Blink\Response\APIResponse;
use Blink\Session;
use Model\QuestionAttempt;

class User {
	public static function get(Request $request, int $id) {
		$user = \User::getByID($id);
		$data = $user -> jsonSerialize();
		$data["correct"] = QuestionAttempt::query()
			-> leftJoin("attempts", "question_attempts.attempt_id", "attempts.id")
			-> where("attempts.user_id", $user -> id)
			-> where("question_attempts.status", QuestionAttempt::ANSWERED)
			-> where("question_attempts.correct", true)
			-> count();

		return new APIResponse(0, "Success!", 200, $data);
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
