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

use RedBeanPHP\Facade as R;

/**
 * Class users
 *
 * @package common\user\legacy
 */
class users
{
	/**
	 * Returns an array of all user beans
	 *
	 * @param string $orderBy Order By $orderBy ASC
	 *
	 * @return array
	 */
	public static function lists($orderBy = "id") : array
	{
		return R::findAll('user', ' ORDER BY ? ASC ', [$orderBy]);
	}

	/**
	 * checks if a user exists
	 *
	 * @param string $username Username
	 *
	 * @return bool
	 */
	public static function isUser($username)
	{
		$userdbname = setup::getValidation("username");
		if (R::findOne('user', ' ' . $userdbname . ' = ? ', [$username])) {
			return true;
		}

		return false;
	}

	/**
	 * Removes an user by his name
	 *
	 * @param string $username Username
	 */
	public static function removeByUsername($username)
	{
		$userdbname = setup::getValidation("username");
		if ($user = R::findOne('user', ' ' . $userdbname . ' = ? ', [$username])) {
			R::trash($user);
		}
	}

	/**
	 * Removes an user by his id
	 *
	 * @param integer $id id of user
	 */
	public static function removeById($id)
	{
		if ($user = R::findOne('user', ' id = ? ', [$id])) {
			R::trash($user);
		}
	}
}
