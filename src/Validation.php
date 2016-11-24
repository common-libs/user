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

namespace common\user;

use common\user\exception\BadCharactesUsernameValidationException;
use common\user\exception\FieldNotAllowedValidationException;
use common\user\exception\MaxLengthValidationException;
use common\user\exception\MinLengthValidationException;
use common\user\exception\NoValidEmailValidationException;
use common\user\exception\RequiredValidationException;
use common\user\exception\UsernameTakenValidationException;
use RedBeanPHP\R;

/**
 * Class Validation
 *
 * @package common\user
 */
class Validation
{
	/**
	 * @var array
	 */
	protected $forms = [];

	/**
	 * create new validation
	 *
	 * @param string $string name
	 *
	 * @return ValidationObject
	 */
	public function name(string $string) : ValidationObject
	{
		if (!isset($this->forms[$string])) {
			$this->forms[$string] = new ValidationObject($string);
		}

		return $this->forms[$string];
	}

	/**
	 * validate a field
	 *
	 * @param string $name  field
	 * @param mixed  $value value
	 *
	 * @return mixed
	 * @throws \common\user\exception\BadCharactesUsernameValidationException
	 * @throws \common\user\exception\FieldNotAllowedValidationException
	 * @throws \common\user\exception\MaxLengthValidationException
	 * @throws \common\user\exception\MinLengthValidationException
	 * @throws \common\user\exception\NoValidEmailValidationException
	 * @throws \common\user\exception\RequiredValidationException
	 * @throws \common\user\exception\UsernameTakenValidationException
	 */
	public function validate(string $name, $value)
	{
		if (!isset($this->forms[$name]) && Config::init()->protectUserFields) {
			throw new FieldNotAllowedValidationException();
		}
		if (!isset($this->forms[$name])) {
			return $value;
		}
		$val = $this->forms[$name]->get();
		if (strlen($value) > $val["max"]) {
			throw new MaxLengthValidationException();
		}
		if (strlen($value) < $val["min"]) {
			throw new MinLengthValidationException();
		}
		if ($val["username"] && preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $value)) {
			throw new BadCharactesUsernameValidationException();
		}
		if ($val["username"] && R::findOne('user', ' username = ? ', [$value])) {
			throw new UsernameTakenValidationException();
		}
		if ($val["required"] && empty($value)) {
			throw new RequiredValidationException();
		}
		if (!empty($val["default"]) && empty($value)) {
			$value = $val["default"];
		}
		if ($val["email"] && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
			throw new NoValidEmailValidationException();
		}
		if ($val["password"]) {
			$value = setup::doHash($value);
		}

		return $value;
	}

	/**
	 * get field name of username|password|email
	 *
	 * @param string $name field
	 *
	 * @return string
	 */
	public function get(string $name) : string
	{
		$forms = $this->forms;
		foreach ($forms as $id => $item) {
			$val = $item->get();
			if (isset($val[$name]) && $val[$name]) {
				return $id;
			}
		}

		return false;
	}

	/**
	 * get one validation parameter from one field
	 *
	 * @param string $field field
	 * @param string $name  validation parameter
	 *
	 * @return mixed
	 */
	public function is(string $field, string $name)
	{
		return $this->forms[$field]->get()[$name];
	}

	/**
	 * get all validation parameters from all fields
	 *
	 * @return array
	 */
	public function getAll() : array
	{
		$forms = $this->forms;
		$a     = [];
		foreach ($forms as $id => $item) {
			$a[$id] = $item->get();
		}

		return $a;
	}
}