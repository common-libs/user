<?php

namespace common\user;

use RedBeanPHP\R;

/**
 * Class user
 *
 * @package common\user
 */
class user
{
	/**
	 * @var bool
	 */
	protected static $setuped = false;
	/**
	 * @var \RedBeanPHP\OODBBean
	 */
	protected $user = false;
	/**
	 * @var bool
	 */
	protected $created;

	/**
	 * user constructor.
	 *
	 * @param string                    $username username
	 * @param null|\RedBeanPHP\OODBBean $bean     user bean
	 */
	public function __construct(string $username, $bean = NULL)
	{
		self::check();
		if (is_null($bean)) {
			$userdbname = setup::getValidation("username");
			$this->user = R::findOne('user', ' ' . $userdbname . ' = ? ', [$username]);
			if (!setup::getValidation($userdbname, "unique") || is_null($this->user)) {
				$this->user                = R::dispense("user");
				$this->user->{$userdbname} = setup::validation($userdbname, $username);
				$this->created             = true;
				R::store($this->user);
			}
		}
		else {
			$this->user = $bean;
		}
	}

	/**
	 * check if table was created
	 */
	public static function check()
	{
		if (R::count("user") < 1) {
			$pwdbname             = setup::getValidation("password");
			$userdbname           = setup::getValidation("username");
			$guest                = R::dispense("user");
			$guest->{$userdbname} = "guest";
			$guest->{$pwdbname}   = "";
			$guest->role          = role::get("guest");
			R::store($guest);
		}
	}

	/**
	 * find a user
	 *
	 * @param string $username
	 * @param string $password
	 *
	 * @return bool|\common\user\user
	 */
	public static function find(string $username, string $password = "")
	{
		self::check();
		$userdbname = setup::getValidation("username");
		if ($password != "") {
			$pwdbname = setup::getValidation("password");
			$bean     = R::findOne('user', ' ' . $userdbname . ' = ? AND ' . $pwdbname . ' = ? ', [
				$username,
				helper::hash($password)
			]);
		}
		else {
			$bean = R::findOne('user', ' ' . $userdbname . ' = ? ', [$username]);
		}
		if (!is_null($bean)) {
			return new self("", $bean);
		}

		return false;
	}

	/**
	 * return a guest user
	 *
	 * @return \common\user\user
	 */
	public static function guest() : user
	{
		self::check();
		$userdbname = setup::getValidation("username");

		return new self("", R::findOne('user', ' ' . $userdbname . ' = ? ', ["guest"]));
	}

	/**
	 * get role of user
	 *
	 * @return \common\user\role
	 */
	public function getRole() : role
	{
		return new role($this->user->role);
	}

	/**
	 * set role of user
	 *
	 * @param string $role rolename
	 */
	public function setRole(string $role)
	{
		$this->user->role = role::get($role);
		R::store($this->user);
	}

	/**
	 * checks if user has a permission
	 *
	 * @param string $permission permission name
	 *
	 * @return bool
	 */
	public function hasPermission(string $permission) : bool
	{
		$role = new role($this->user->role->name);

		return $role->hasPermission($permission);
	}

	/**
	 * magic field for get value of user
	 *
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function __get(string $name)
	{
		return $this->user->$name;
	}

	/**
	 *  magic field for add value to user
	 *
	 * @param string $name
	 * @param mixed  $value
	 */
	public function __set(string $name, $value)
	{
		$this->user->$name = setup::validation($name, $value);

		R::store($this->user);
	}

	/**
	 * auto created missing field
	 */
	public function __destruct()
	{
		if ($this->created && Config::init()->createdMissingUserFields) {
			$a = [];
			foreach ($this->user as $id => $properties) {
				$a[$id] = $properties;
			}
			foreach (array_diff_key(setup::validation(), $a) as $id => $item) {
				$this->user->$id = $item["default"];
			}
			R::store($this->user);
		}
	}
}