<?php
namespace common\user;
/**
 * Class RoleObject
 *
 * @package common\user
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
	public function __construct(string $string)
	{
		$this->role = new role($string);
	}

	/**
	 * add permissions to role
	 *
	 * @param array $permissions Array of permissions
	 *
	 * @return $this|\common\user\RoleObject
	 */
	public function permissions(array $permissions) : RoleObject
	{
		$this->role->addPermissions($permissions);

		return $this;
	}
}