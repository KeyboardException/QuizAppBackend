<?php

namespace Controller;
use User;

class Sandbox {
	public static function hello() {
		return "Hello World!";
	}

	public static function test() {
		$users = User::query() -> where("id", "=", 1) -> first();
		return $users;
	}
}
