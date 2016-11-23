<?php
namespace common\user;
/**
 * Class Roles
 *
 * @package common\user
 */
class Roles
{
	/**
	 * @var array
	 */
	protected $role = [];

	/**
	 * create new role
	 *
	 * @param string $string name of role
	 *
	 * @return \common\user\RoleObject
	 */
	public function name(string $string) : RoleObject
	{
		$this->role[] = $role = new RoleObject($string);

		return $role;
	}
}