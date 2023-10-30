<?php

namespace Controller;
use Blink\Response;
use Model\Question;
use Model\QuestionBank;
use User;

class Sandbox {
	public static function hello() {
		return "Hello World!";
	}

	public static function test() {
		return randItem([1, 2]);
	}

	public static function sync() {
		$banks = QuestionBank::all();

		foreach ($banks as $bank) {
			$bank -> questions = Question::where("bank_id", $bank -> id) -> count();
			$bank -> save();
		}

		return new Response("OK");
	}
}
