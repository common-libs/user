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

namespace common\user\legacy;

/**
 * Class auth
 *
 * @package common\user\legacy
 */
class auth {
	/**
	 * @var user user object
	 */
	protected static $user;
	/**
	 * @var boolean is the user logged in
	 */
	protected static $isGuest;

	/**
	 * @brief init the class and set php session
	 */
	public static function init() {
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
		if (isset($_SESSION["username"]) && !empty($_SESSION["username"])) {
			self::$user    = user::find($_SESSION["username"]);
			self::$isGuest = false;
		}
		else {
			self::$user    = user::guest();
			self::$isGuest = true;
		}
	}

	/**
	 * get the user
	 *
	 * @return \common\user\legacy\user
	 */
	public static function user() {
		return self::$user;
	}

	/**
	 * check if the current user is guest
	 *
	 * @return bool
	 */
	public static function isGuest() {
		return self::$isGuest;
	}

	/**
	 * @param string $username username
	 * @param string $password password
	 *
	 * @return bool
	 */
	public static function login($username, $password) {
		if ($user = user::find($username, $password)) {
			self::$user           = $user;
			self::$isGuest        = false;
			$_SESSION["username"] = $username;

			return true;
		}

		return false;
	}

	/**
	 * logout the current user
	 */
	public static function logout() {
		$_SESSION = [];
		session_destroy();
		session_unset();
		self::$isGuest = true;
		self::$user = user::guest();
	}

	/**
	 * check if current user has a permission
	 *
	 * @param string $permission name of permission
	 *
	 * @return bool
	 */
	public static function requires($permission) {
		if (empty($permission)) {
			return true;
		}

		return self::$user->hasPermission($permission);
	}

	/**
	 * check if current user has a permission
	 *
	 * @param string $permission name of permission
	 *
	 */
	public static function required($permission) {
		if (!self::$user->hasPermission($permission)) {
			die("No permission. Quit.");
		}
	}
}