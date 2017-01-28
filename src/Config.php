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

use Closure;
use common\storage\file;
use common\storage\json;
use common\storage\storage;


/**
 * Class Config
 *
 * @package common\user
 *
 * @property string protectUserFields
 * @property bool   createdMissingUserFields
 * @property string secret
 */
class Config extends storage {
	use json;

	/**
	 * @return Closure
	 */
	public static function setConfig() {
		return function (file $options) {
			$options->setCreateIfNotExists(true);
			$options->setPath(getcwd() . "/.common/user/config.json");
			$options->setCache(true);
		};
	}
}