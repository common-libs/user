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

/**
 * Class helper
 *
 * @package common\user
 */
class helper {
	/**
	 * Hashes an string/password
	 *
	 * @param string $str String to hash
	 *
	 * @return string
	 */
	public static function hash(string $str): string {
		if (empty(Config::init()->secret)) {
			Config::init()->secret = uniqid();
		}

		return hash("sha512", Config::init()->secret . $str . "common-libs" . $str . "user");
	}
}