<?php

namespace common\user;


/**
 * Class helper
 *
 * @package common\user
 */
class helper
{
	/**
	 * Hashes an string/password
	 *
	 * @param string $str String to hash
	 *
	 * @return string
	 */
	public static function hash(string $str) : string
	{
		if (empty(Config::init()->secret)) {
			Config::init()->secret = uniqid();
		}

		return hash("sha512", Config::init()->secret . $str . "common-libs" . $str . "user");
	}
}