<?php

App::uses('CloggyAppModel','Cloggy.Model');
App::uses('CloggyUserLogin','Cloggy.Model');

class CloggyUserLoginTest extends CakeTestCase {
	
	public $fixtures = array(
		'plugin.cloggy.cloggy_user',
		'plugin.cloggy.cloggy_user_login',
		'plugin.cloggy.cloggy_user_meta'
	);
	
	private $__CloggyUserLogin;
	
	public function setUp() {
		parent::setUp();
		$this->__CloggyUserLogin = ClassRegistry::init('CloggyUserLogin');
		$this->__CloggyUserLogin->cacheQueries = false;
	}
	
	public function testNodeObjects() {
		$this->assertFalse(empty($this->__CloggyUserLogin));
		$this->assertTrue(is_a($this->__CloggyUserLogin,'CloggyUserLogin'));
	}
	
	public function testSetLogin() {
		
		$loginId = $this->__CloggyUserLogin->setLogin(2);
		$checkLogin = $this->__CloggyUserLogin->isLogin(1);
		$checkLoginExists = $this->__CloggyUserLogin->setLogin(1);
		
		$this->assertEqual($loginId,2);
		$this->assertTrue($checkLogin);
		$this->assertFalse($checkLoginExists);
		
	}
	
	public function testRemoveLogin() {
		
		$this->__CloggyUserLogin->removeLogin(1);
		$checkLogin = $this->__CloggyUserLogin->isLogin(1);
		
		$this->assertFalse($checkLogin);
		
	}
	
}