<?php
/**
 * Copyright (c) 2016 Profenter Systems
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

use RedBeanPHP\R;

/**
 * Class user
 *
 * @package common\user\legacy
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
	public function __construct($username, $bean = NULL)
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
	 * @return bool|\common\user\legacy\user
	 */
	public static function find($username, $password = "")
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
	 * @return \common\user\legacy\user
	 */
	public static function guest()
	{
		self::check();
		$userdbname = setup::getValidation("username");

		return new self("", R::findOne('user', ' ' . $userdbname . ' = ? ', ["guest"]));
	}

	/**
	 * get role of user
	 *
	 * @return \common\user\legacy\role
	 */
	public function getRole()
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
	public function hasPermission(string $permission)
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
	public function __get($name)
	{
		return $this->user->$name;
	}

	/**
	 *  magic field for add value to user
	 *
	 * @param string $name
	 * @param mixed  $value
	 */
	public function __set($name, $value)
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