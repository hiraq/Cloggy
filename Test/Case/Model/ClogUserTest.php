<?php

App::uses('ClogAppModel','Clog.Model');
App::uses('ClogUser', 'Clog.Model');

class ClogUserTest extends CakeTestCase {
	
	public $fixtures = array(
		'plugin.clog.clog_user',
		'plugin.clog.clog_user_login',
		'plugin.clog.clog_user_meta',
		'plugin.clog.clog_node',
		'plugin.clog.clog_node_type',
	);
	
	private $_ClogUser;
	
	public function setUp() {
		parent::setUp();
		$this->_ClogUser = ClassRegistry::init('ClogUser');
		$this->_ClogUser->cacheQueries = false;
	}

	public function testSingleModel() {
		
		$data = $this->_ClogUser->find('all',array(
			'contain' => false
		));
		
		$this->assertFalse(empty($data));
		$this->assertInternalType('array',$data);
		$this->assertCount(1,$data);
		
	}
	
	public function testSingleModelFirst() {
		
		$data = $this->_ClogUser->find('first',array(
			'contain' => false
		));
		
		$this->assertFalse(empty($data));
		$this->assertInternalType('array',$data);
		$this->assertCount(1,$data);
		
	}
	
	public function testContained() {
		
		$data = $this->_ClogUser->find('first');
		
		$this->assertFalse(empty($data));
		$this->assertInternalType('array',$data);
		$this->assertCount(5,$data);
		$this->assertCount(4,$data['ClogNode']);
		$this->assertCount(2,$data['ClogNodeType']);
		$this->assertCount(1,$data['ClogUserMeta']);
		$this->assertFalse(empty($data['ClogUserLogin']));		
		
	}
	
	public function testNameExists() {
		
		$check = $this->_ClogUser->isUserNameExists('test user');
		
		$this->assertInternalType('boolean',$check);
		$this->assertTrue($check);
		
		$check = $this->_ClogUser->isUserNameExists('test user exists');
		$this->assertFalse($check);
		
	}
	
	public function testEmailExists() {
		
		$check = $this->_ClogUser->isUserEmailExists('test@email.com');
		
		$this->assertInternalType('boolean',$check);
		$this->assertTrue($check);
		
		$check = $this->_ClogUser->isUserNameExists('test34@email.com');
		$this->assertFalse($check);
		
	}
	
	public function testUserStatus() {
		
		$data = $this->_ClogUser->getUserStatus('1');
		
		$this->assertInternalType('array',$data);
		$this->assertFalse(empty($data));
		$this->assertArrayHasKey('ClogUser',$data);
		$this->assertArrayHasKey('user_status',$data['ClogUser']);
		$this->assertCount(1,$data['ClogUser']);
		$this->assertEqual($data['ClogUser']['user_status'],1);
		
	}
	
	public function testUserLastLogin() {
		
		$data = $this->_ClogUser->getUserLastLogin('1');
		
		$this->assertInternalType('array',$data);
		$this->assertFalse(empty($data));
		$this->assertArrayHasKey('ClogUser',$data);
		$this->assertArrayHasKey('user_last_login',$data['ClogUser']);
		
	}
	
	public function testUserRole() {
		
		$data = $this->_ClogUser->getUserRole('1');
		
		$this->assertInternalType('array',$data);
		$this->assertFalse(empty($data));
		$this->assertArrayHasKey('ClogUser',$data);
		$this->assertArrayHasKey('user_role',$data['ClogUser']);
		$this->assertEqual($data['ClogUser']['user_role'],'super administrator');
		
	}
	
	public function testWrite() {
		
		$this->_ClogUser->create();
		$this->_ClogUser->save(array(
			'ClogUser' => array(
				'user_name' => 'test user 2',
				'user_password' => 'testing 2',
				'user_email' => 'test2@email.com',
				'user_role' => 'super administrator',
				'user_status' => '1',
				'user_last_login' => date('c'),
				'user_created' => date('c'),
				'user_updated' => time()
			)
		));
		
		$insertId = $this->_ClogUser->id;
		
		$data = $this->_ClogUser->find('first',array(
			'contain' => false,
			'conditions' => array('ClogUser.id' => $insertId)
		));
		
		$total = $this->_ClogUser->find('count');
		
		$this->assertFalse(empty($data));
		$this->assertEqual($total,2);
		$this->assertEqual($data['ClogUser']['user_name'],'test user 2');
		
	}
	
	public function testRemove() {
		
		$this->_ClogUser->delete(1,false);
		
		$total = $this->_ClogUser->find('count');		
		$this->assertEqual($total,0);
		
	}
	
}