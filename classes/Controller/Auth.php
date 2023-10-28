<?php

namespace Controller;

use Blink\Request;

class Auth {
	public static function login(Request $request) {
		$username = $request -> requiredParam("username");
		$password = $request -> requiredParam("password");
	}
}
