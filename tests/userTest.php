<?php

require_once("../vendor/autoload.php");
use \RedBeanPHP\Facade as R;

class userTest extends PHPUnit_Framework_TestCase {

	public function __construct() {
		if (function_exists('sqlite_open')) {
			R::setup('sqlite:test' . md5(time()) . '.db');
			R::debug(true);
			ob_start();
			\common\user\auth::init();
		} else {
			$this->markTestSkipped("No SqLite extension installed");
			$this->fail("No Sqlite");
		}

		parent::__construct();
	}

	public function testCreateUser() {
		$user = new \common\user\user("test");
		$user->test = "testField";
		$user->password = "test";
		$this->assertSame($user->test,"test");
	}

	public function testUserFind() {

		$this->assertEmpty(session_id());

		$this->assertNotEmpty(session_id());

	}
}



