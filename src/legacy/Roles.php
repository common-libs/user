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