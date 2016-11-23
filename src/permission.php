<?php

namespace common\user;


use RedBeanPHP\OODBBean;
use RedBeanPHP\R;

/**
 * Class permission
 *
 * @package common\user
 */
class permission
{
	/**
	 * @var \RedBeanPHP\OODBBean
	 */
	protected $permission;

	/**
	 * permission constructor.
	 *
	 * auto creates a new permission if necessary
	 *
	 * @param $name
	 */
	public function __construct(string $name)
	{
		self::check();
		if (!$permission = R::findOne("permission", " name = ? ", [$name])) {
			$permission       = R::dispense("permission");
			$permission->name = $name;
			R::store($permission);
		}
		$this->permission = $permission;
	}

	/**
	 * checks if table is created
	 */
	public static function check()
	{
		if (R::count("permission") < 1) {
			$role       = R::dispense("permission");
			$role->name = "guest";
			R::store($role);
		}
	}

	/**
	 * return permission db object
	 *
	 * @return \RedBeanPHP\OODBBean
	 */
	public function getPermission() : OODBBean
	{
		return $this->permission;
	}

	/**
	 * alias for getPermission
	 *
	 * @return \RedBeanPHP\OODBBean
	 */
	public function get() : OODBBean
	{
		return $this->getPermission();
	}
}