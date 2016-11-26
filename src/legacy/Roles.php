<?php
namespace common\user\legacy;
/**
 * Class Roles
 *
 * @package common\user\legacy
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
	 * @return \common\user\legacy\RoleObject
	 */
	public function name($string)
	{
		$this->role[] = $role = new RoleObject($string);

		return $role;
	}
}