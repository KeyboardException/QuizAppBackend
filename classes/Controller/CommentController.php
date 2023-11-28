<?php

namespace Controller;

use Blink\Request;
use Blink\Response\APIResponse;
use Blink\Session;
use Model\Comment;
use User;

class CommentController {
	public static function fetch(Request $request, int $profileId) {
		Session::requireLogin();

		$profile = User::getByID($profileId);
		$comments = Comment::where("profileid", $profile -> id)
			-> sort("created", "DESC")
			-> all();

		return new APIResponse(0, "Success!", 200, $comments);
	}

	public static function create(Request $request, int $profileId) {
		Session::requireLogin();

		$profile = User::getByID($profileId);
		$score = $request -> requiredParam("score", TYPE_FLOAT);
		$content = $request -> requiredParam("content", TYPE_TEXT);

		$comment = Comment::create(Array(
			"profile" => $profile,
			"author" => Session::$user,
			"score" => $score,
			"content" => $content
		)) -> save();

		return new APIResponse(0, "Success!", 200, $comment);
	}
}
