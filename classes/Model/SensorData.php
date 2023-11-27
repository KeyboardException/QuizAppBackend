<?php

namespace Model;

use Blink\Model;


/**
 * QuestionBank model
 * 
 * @extends	Model<SensorData>
 */
class SensorData extends Model {
	public static String $table = "sensor_datas";

	public static $fillables = Array(
		"id",
		"type",
		"value",
		"created"
	);

	public int $id;

	public String $type;

	public float $value;

	public int $created;
}
