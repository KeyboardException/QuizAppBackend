<?php

namespace Controller;

use Blink\Request;
use Blink\Response\APIResponse;

class QuestionBank {
	public static function list(Request $request) {
		return new APIResponse(0, "Success!", 200, \Model\QuestionBank::all());
	}
}
