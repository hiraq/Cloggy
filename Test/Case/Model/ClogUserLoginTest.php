<?php

App::uses('ClogAppModel','Clog.Model');
App::uses('ClogUserLogin', 'Clog.Model');

class ClogUserLoginTest extends CakeTestCase {
	
	public $fixtures = array(
		'plugin.clog.clog_user',
		'plugin.clog.clog_user_login'		
	);
	
	private $_ClogUserLogin;
	
	public function setUp() {
		parent::setUp();
		$this->_ClogUserLogin = ClassRegistry::init('ClogUserLogin');
		$this->_ClogUserLogin->cacheQueries = false;
	}
	
	public function testSingleModel() {
		
		$data = $this->_ClogUserLogin->find('all',array(
			'contain' => false
		));
		
		$this->assertFalse(empty($data));
		$this->assertInternalType('array',$data);
		$this->assertCount(1,$data);
		
	}
	
	public function testSingleModelFirst() {
		
		$data = $this->_ClogUserLogin->find('first',array(
			'contain' => false
		));
		
		$this->assertFalse(empty($data));
		$this->assertInternalType('array',$data);
		$this->assertCount(1,$data);
		
	}
	
	public function testContained() {
		
		$data = $this->_ClogUserLogin->find('first');
		
		$this->assertFalse(empty($data));
		$this->assertInternalType('array',$data);
		$this->assertCount(2,$data);
		$this->assertArrayHasKey('ClogUser',$data);
		$this->assertArrayHasKey('ClogUserLogin',$data);
		$this->assertFalse(empty($data['ClogUser']));
		$this->assertFalse(empty($data['ClogUserLogin']));
		
	}
	
	public function testUserIsLogin() {
		
		$check = $this->_ClogUserLogin->isLogin(1);
		
		$this->assertInternalType('boolean',$check);
		$this->assertTrue($check);
		
		$check = $this->_ClogUserLogin->isLogin(2);
		$this->assertFalse($check);
		
	}
	
	public function testWrite() {
		
		$save = $this->_ClogUserLogin->setLogin(1);
		
		$this->assertInternalType('boolean',$save);
		$this->assertFalse($save);
		
		$save = $this->_ClogUserLogin->setLogin(2);		
		$this->assertEqual($save,2);
		
	}
	
	public function testRemove() {
		
		$this->_ClogUserLogin->delete(1,false);
		
		$data = $this->_ClogUserLogin->find('count');		
		$this->assertEqual($data,0);
		
	}
	
}