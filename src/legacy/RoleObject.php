<?php
namespace common\user\legacy;
/**
 * Class RoleObject
 *
 * @package common\user\legacy
 */
class RoleObject
{
	/**
	 * @var role
	 */
	protected $role;

	/**
	 * ValidationObject constructor.
	 *
	 * @param string $string name of role
	 */
	public function __construct($string)
	{
		$this->role = new role($string);
	}

	/**
	 * add permissions to role
	 *
	 * @param array $permissions Array of permissions
	 *
	 * @return $this|\common\user\legacy\RoleObject
	 */
	public function permissions($permissions)
	{
		$this->role->addPermissions($permissions);

		return $this;
	}
}