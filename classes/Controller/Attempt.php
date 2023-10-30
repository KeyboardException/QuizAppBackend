<?php

namespace Controller;

use Blink\Model;
use Blink\Request;
use Blink\Response\APIResponse;
use Blink\Session;
use Model\QuestionAttempt;
use Model\QuestionBank;

class Attempt {
	public static function start(Request $request, int $bankId) {
		Session::requireLogin();
		
		$bank = QuestionBank::getByID($bankId, Model::MUST_EXIST);
		return \Model\Attempt::start(Session::$user, $bank);
	}

	public static function answer(Request $request, int $attemptId, int $qaId) {
		Session::requireLogin();

		$answer = $request -> requiredParam("answer", TYPE_INT);
		$attempt = \Model\Attempt::getByID($attemptId, Model::MUST_EXIST);
		$qa = QuestionAttempt::getByID($qaId, Model::MUST_EXIST);
		$data = $attempt -> answer($qa, $answer);
		return new APIResponse(0, "Attempt state updated", 200, $data);
	}
}
