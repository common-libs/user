<?php

require_once("../vendor/autoload.php");
use \RedBeanPHP\Facade as R;

class authTest extends PHPUnit_Framework_TestCase
{

	public function __construct () {
		if (function_exists('sqlite_open')) {
			R::setup('sqlite:test.db');
			R::debug(true);
			ob_start();
		} else {
			$this->fail("No Sqlite");
		}

		parent::__construct();
	}

	public function testSessionSet()
	{

		$this->assertEmpty(session_id());
		\common\user\auth::init();
		$this->assertNotEmpty(session_id());

	}
}



