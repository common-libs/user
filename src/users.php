<?php


namespace common\user;

use RedBeanPHP\Facade as R;

class users
{
	/**
	 * Returns an array of all user beans
	 *
	 * @param string $orderBy Order By $orderBy ASC
	 *
	 * @return array
	 */
	public static function lists(string $orderBy = "id") : array
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
	public static function isUser(string $username) : bool
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
	public static function removeByUsername(string $username)
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
	public static function removeById(int $id)
	{
		if ($user = R::findOne('user', ' id = ? ', [$id])) {
			R::trash($user);
		}
	}
}
