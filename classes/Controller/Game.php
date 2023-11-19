<?php

namespace Controller;
use Blink\Middleware\Request;
use Blink\Session;

class Game {
	public static function queue(Request $request) {
		Session::requireLogin();

		
	}

	public static function stopQueue(Request $request) {
		Session::requireLogin();
	}
}
