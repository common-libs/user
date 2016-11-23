<?php
namespace common\user;
use Closure;
use common\storage\storage;
use common\storage\file;
use common\storage\json;


/**
 * Class Config
 *
 * @package common\user
 *
 * @property string protectUserFields
 * @property bool   createdMissingUserFields
 * @property string secret
 */
class Config extends storage
{
	use json;
	/**
	 * @return Closure
	 */
	public static function setConfig()
	{
		return function (file $options) {
			$options->setCreateIfNotExists(true);
			$options->setPath(getcwd()."/.common/user/config.json");
			$options->setCache(true);
		};
	}
}