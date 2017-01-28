<?php
/**
 * Copyright (c) 2017 Profenter Systems
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace common\user;

use Closure;

/**
 * Class setup
 *
 * @package common\user
 */
class setup {
	/**
	 * @var Validation
	 */
	protected static $validate;
	/**
	 * @var Roles
	 */
	protected static $role;
	/**
	 * @var Closure
	 */
	protected static $hash;

	/**
	 * setup validation
	 *
	 * @param \Closure $param function to call for validation
	 */
	public static function validate(Closure $param) {
		call_user_func($param, $validation = new Validation());
		self::$validate = $validation;
	}

	/**
	 * do validation
	 *
	 * calling without parameters this method returns all validation parameters
	 * calling with two parameters this method validate an value
	 *
	 * @param bool|string $name  name of field
	 * @param bool|string $value value of field
	 *
	 * @return array|string
	 */
	public static function validation($name = false, $value = false) {
		if (!$name && !$value) {
			return self::$validate->getAll();
		}

		return self::$validate->validate($name, $value);
	}

	/**
	 * get validation field(s)
	 *
	 * calling with one parameters this method returns the field name of username|password|email
	 * calling with two parameters this method returns one validation parameter from one field
	 *
	 * @param string $name  field name
	 * @param bool   $value field parameter
	 *
	 * @return mixed|string
	 */
	public static function getValidation(string $name, $value = false) {
		return $value !== false ? self::$validate->is($name, $value) : self::$validate->get($name);
	}

	/**
	 * setup roles
	 *
	 * @param Closure $param function to call for role creating
	 */
	public static function roles($param) {
		call_user_func($param, $role = new Roles());
		self::$role = $role;
	}

	/**
	 * setup hash function
	 *
	 * @param Closure $param function to call for hash
	 */
	public static function hash(Closure $param) {
		self::$hash = $param;
	}

	/**
	 * hashes a sting to custom hash function
	 *
	 * @param string $password string to hash
	 *
	 * @return string
	 */
	public static function doHash(string $password): string {
		return call_user_func(self::$hash, $password);
	}

	/**
	 * setup config
	 *
	 * @param \Closure $param function to call for config setup
	 */
	public static function config(Closure $param) {
		call_user_func($param, new Config());
	}
}