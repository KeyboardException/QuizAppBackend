<?php

namespace Controller;

use Blink\Request;
use Blink\Response;
use Blink\Response\APIResponse;
use Model\SensorData;
use User;

class SensorController {
	public static function save(Request $request) {
		$type = $request -> requiredParam("type", TYPE_TEXT);
		$value = $request -> requiredParam("value", TYPE_FLOAT);

		$instance = SensorData::create(Array(
			"type" => $type,
			"value" => $value
		));

		$instance -> save();
		return new APIResponse(0, "Success!", 200, $instance);
	}

	public static function fetch(Request $request) {
		$values = SensorData::query()
			-> sort("created", "DESC")
			-> all(0, 20);

		return new APIResponse(0, "Success!", 200, $values);
	}
}
