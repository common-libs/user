<?php

namespace common\user;


use common\storage\legacy\serialize;
use RedBeanPHP\OODBBean;
use RedBeanPHP\R;

/**
 * Class role
 *
 * @package common\user
 */
class role
{

	/**
	 * @var OODBBean
	 */
	protected $role;

	/**
	 * role constructor.
	 *
	 * @param string $roleName
	 */
	public function __construct(string $roleName)
	{
		self::check();
		if (!$role = R::findOne("role", " name = ? ", [$roleName])) {
			$role       = R::dispense("role");
			$role->name = $roleName;
			R::store($role);
		}
		$this->role = $role;
	}

	/**
	 * alias for new role($role)
	 *
	 * @param string $role role name
	 *
	 * @return \RedBeanPHP\OODBBean
	 */
	public static function get(string $role) : OODBBean
	{
		self::check();
		$r = new self($role);

		return $r->getRole();
	}

	/**
	 * checks if given role object is equal to current one
	 *
	 * @param \common\user\role $role role object
	 *
	 * @return bool
	 */
	public function equals(role $role) : bool
	{
		return $role->getRole()->id == $this->role->id && $role->getRole()->name == $this->role->name;
	}

	/**
	 * check if table was created
	 */
	public static function check()
	{
		if (R::count("role") < 1) {
			$role                             = R::dispense("role");
			$role->name                       = "guest";
			$per                              = new permission("guest");
            $role->role->sharedPermissionList [] = $per->get();
			R::store($role);
		}
	}

	/**
	 * add permissions to role
	 *
	 * @param array $permissions array of permission names
	 */
	public function addPermissions(array $permissions)
	{
		foreach ($permissions as $permission) {
			$per                              = new permission($permission);
			$this->role->sharedPermissionList [] = $per->get();
		}
		R::store($this->role);
	}

	/**
	 * add a permission to role
	 *
	 * @param string $permission permission name
	 */
	public function addPermission(string $permission)
	{
		$per                              = new permission($permission);
		$this->role->sharedPermissionList [] = $per->get();
		R::store($this->role);
	}

	/**
	 * return all permission assigned to this role
	 *
	 * @return array
	 */
	public function getPermissions() : array
	{
		$permissions = [];
		foreach ($this->role->sharedPermissionList as $permission) {
			$permissions[] = $permission->name;
		}

		return $permissions;
	}

	/**
	 * get db role object
	 *
	 * @return OODBBean
	 */
	public function getRole(): OODBBean
	{
		return $this->role;
	}

	/**
	 * check if a role has a permission
	 *
	 * @param string $permissionName name of permission
	 *
	 * @return bool
	 */
	public function hasPermission(string $permissionName) : bool
	{
		foreach ($this->role->sharedPermissionList as $permission) {
			if ($permission->name == $permissionName) {
				return true;
			}
		}

		return false;
	}
}