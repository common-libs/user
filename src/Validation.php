<?php
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