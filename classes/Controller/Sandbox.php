<?php

namespace Controller;
use User;

class Sandbox {
	public static function hello() {
		return "Hello World!";
	}

	public static function test() {
		return randItem([1, 2]);
	}
}
