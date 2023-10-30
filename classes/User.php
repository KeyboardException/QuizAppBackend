<?php

use Blink\Exception\CodingError;
use Blink\Exception\ModelInstanceNotFound;
use Blink\Model;

class User extends \Model\User {

	/**
	 * Get instance of User, By ID.
	 *
	 * @param	int		$id
	 * @param	int		$strict		Strictness. Default to IGNORE_MISSING, which will return null when instance not found.
	 * @return	?User
	 */
	public static function getByID(
		?int $id = null,
		int $strict = Model::IGNORE_MISSING
	): static|null {
		if (static::class === self::class || static::class === 'Vloom\DB')
			throw new CodingError(self::class . "::getByID(): this function can only be used on an inherited Model.");

		if (empty($id)) {
			if ($strict === Model::MUST_EXIST)
				throw new ModelInstanceNotFound(static::class, $id);

			return null;
		}

		$instance = static::getInstance($id);

		if (!empty($instance))
			return $instance;

		$instance = static::where(static::$primaryKey, $id);
		$instance = $instance -> first();

		if (empty($instance)) {
			if ($strict === Model::MUST_EXIST)
				throw new ModelInstanceNotFound(static::class, $id);

			return null;
		}

		return $instance;
	}

	public static function getByUsername(String $username): User|null {
		return static::where("username", $username)
			-> first();
	}
}
