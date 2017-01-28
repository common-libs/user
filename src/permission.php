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

namespace common\user;

use RedBeanPHP\OODBBean;
use RedBeanPHP\R;

/**
 * Class permission
 *
 * @package common\user
 */
class permission {
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
	public function __construct(string $name) {
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
	public static function check() {
		if (R::count("permission") < 1) {
			$role       = R::dispense("permission");
			$role->name = "guest";
			R::store($role);
		}
	}

	/**
	 * alias for getPermission
	 *
	 * @return \RedBeanPHP\OODBBean
	 */
	public function get(): OODBBean {
		return $this->getPermission();
	}

	/**
	 * return permission db object
	 *
	 * @return \RedBeanPHP\OODBBean
	 */
	public function getPermission(): OODBBean {
		return $this->permission;
	}
}