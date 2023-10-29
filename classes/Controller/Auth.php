<?php

namespace Controller;

use Blink\Exception\UserNotFound;
use Blink\Exception\WrongPassword;
use Blink\Request;
use Blink\Response\APIResponse;
use Blink\Session;
use Exception;
use User;

class Auth {
	public static function session(Request $request) {
		$data = Array(
			"authenticated" => false,
			"session" => session_id(),
			"method" => Session::$method,
			"token" => Session::$token ?-> token,
			"expire" => Session::$token ?-> expire,
			"user" => Session::$user
		);

		if (Session::loggedIn()) {
			$username = Session::$username;
			$data["authenticated"] = true;

			return new APIResponse(0, "Logged in as {$username}", 200, $data);
		}

		return new APIResponse(NOT_LOGGED_IN, "You are not logged in", 401, $data);
	}

	public static function login(Request $request) {
		$username = $request -> requiredParam("username");
		$password = $request -> requiredParam("password");

		$user = User::where("username", $username) -> first();

		if (empty($user))
			throw new UserNotFound(Array( "username" => $username ));

		if (!password_verify($password, $user -> password))
			throw new WrongPassword($user -> username);

		Session::completeLogin($user);
		$token = Session::createToken($user);

		return new APIResponse(0, "Logged in as {$username}", 200, Array(
			"session" => session_id(),
			"token" => $token -> token,
			"expire" => $token -> expire,
			"user" => $user
		));
	}

	public static function register(Request $request) {
		$username = $request -> requiredParam("username");
		$name = $request -> requiredParam("name");
		$email = $request -> requiredParam("email");
		$password = $request -> requiredParam("password");

		if (User::where("username", $username) -> exists())
			throw new Exception("User already exist with username {$username}");

		$user = User::create(Array(
			"username" => $username,
			"name" => $name,
			"email" => $email,
			"password" => $password
		)) -> save();

		Session::completeLogin($user);
		$token = Session::createToken($user);

		return new APIResponse(0, "Created new account for user {$username}", 200, Array(
			"session" => session_id(),
			"token" => $token -> token,
			"expire" => $token -> expire,
			"user" => $user
		));
	}
}
