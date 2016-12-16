<?php

namespace common\user;

/**
 * Class auth
 *
 * @package common\user
 */
class auth
{
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
	public static function init()
	{
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
	 * @return \common\user\user
	 */
	public static function user()
	{
		return self::$user;
	}

	/**
	 * check if the current user is guest
	 *
	 * @return bool
	 */
	public static function isGuest()
	{
		return self::$isGuest;
	}

	/**
	 * @param string $username username
	 * @param string $password password
	 *
	 * @return bool
	 */
	public static function login(string $username, string $password) : bool
	{
		if ($user = user::find($username, $password)) {
			self::$user    = $user;
			self::$isGuest = false;

			return true;
		}

		return false;
	}

	/**
	 * logout the current user
	 */
	public static function logout()
	{
		$_SESSION = [];
		session_destroy();
		session_unset();
		self::$isGuest = true;
	}

	/**
	 * check if current user has a permission
	 *
	 * @param string $permission name of permission
	 *
	 * @return bool
	 */
	public static function require(string $permission): bool
	{
        if(empty($permission))
            return true;
		return self::$user->hasPermission($permission);
	}

	/**
	 * check if current user has a permission
	 *
	 * @param string $permission name of permission
	 *
	 */
	public static function required(string $permission)
	{
		if (!self::$user->hasPermission($permission)) {
			die("No permission. Quit.");
		}
	}
}