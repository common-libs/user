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
 * Class ValidationObject
 *
 * @package common\user\legacy
 */
class ValidationObject
{
	/**
	 * @var int
	 */
	protected $min = 0;
	/**
	 * @var int
	 */
	protected $max = 255;
	/**
	 * @var string|boolean|array|integer
	 */
	protected $default = "";
	/**
	 * @var bool
	 */
	protected $required = false;
	/**
	 * @var bool
	 */
	protected $email = false;
	/**
	 * @var bool
	 */
	protected $password = false;
	/**
	 * @var bool
	 */
	protected $username = false;
	/**
	 * @var bool
	 */
	protected $unique = false;

	/**
	 * ValidationObject constructor.
	 *
	 * @param $string
	 */
	public function __construct($string)
	{
		$this->name = $string;
	}

	/**
	 * set min characters
	 *
	 * @param int $min minimum size
	 *
	 * @return $this|\common\user\legacy\ValidationObject
	 */
	public function min($min)
	{
		$this->min = $min;

		return $this;
	}

	/**
	 * set max characters
	 *
	 * @param int $max maximum size
	 *
	 * @return $this|\common\user\legacy\ValidationObject
	 */
	public function max($max)
	{
		$this->max = $max;

		return $this;
	}

	/**
	 * if empty set this value
	 *
	 * @param mixed $default default value
	 *
	 * @return $this|\common\user\legacy\ValidationObject
	 */
	public function defaults($default)
	{
		$this->default = $default;

		return $this;
	}

	/**
	 * cannot be empty
	 *
	 * @return $this|\common\user\legacy\ValidationObject
	 */
	public function required()
	{
		$this->required = true;

		return $this;
	}

	/**
	 * this is email field
	 *
	 * @return $this|\common\user\legacy\ValidationObject
	 */
	public function email()
	{
		$this->email = true;

		return $this;
	}

	/**
	 * this is password field
	 *
	 * @return $this|\common\user\legacy\ValidationObject
	 */
	public function password()
	{
		$this->password = true;

		return $this;
	}

	/**
	 * this is username field
	 *
	 * @return $this|\common\user\legacy\ValidationObject
	 */
	public function username()
	{
		$this->username = true;

		return $this;
	}

	/**
	 * no duplicate of this field in database
	 *
	 * @return $this|\common\user\legacy\ValidationObject
	 */
	public function unique()
	{
		$this->unique = true;

		return $this;
	}

	/**
	 * @return array
	 */
	public function get()
	{
		return [
			"min"      => $this->min,
			"max"      => $this->max,
			"username" => $this->username,
			"password" => $this->password,
			"email"    => $this->email,
			"default"  => $this->default,
			"required" => $this->required,
			"unique"   => $this->unique,
		];
	}
}