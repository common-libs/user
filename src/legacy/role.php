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


use RedBeanPHP\OODBBean;
use RedBeanPHP\R;

/**
 * Class role
 *
 * @package common\user\legacy
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
	public function __construct($roleName)
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
	public static function get($role)
	{
		self::check();
		$r = new self($role);

		return $r->getRole();
	}

	/**
	 * checks if given role object is equal to current one
	 *
	 * @param \common\user\legacy\role $role role object
	 *
	 * @return bool
	 */
	public function equals($role)
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
	public function addPermissions($permissions)
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
	public function addPermission($permission)
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
	public function getPermissions()
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
	public function getRole()
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
	public function hasPermission($permissionName)
	{
		foreach ($this->role->sharedPermissionList as $permission) {
			if ($permission->name == $permissionName) {
				return true;
			}
		}

		return false;
	}
}