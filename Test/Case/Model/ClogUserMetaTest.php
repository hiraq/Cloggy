<?php

App::uses('ClogAppModel','Clog.Model');
App::uses('ClogUserMeta', 'Clog.Model');

class ClogUserMetaTest extends CakeTestCase {
	
	public $fixtures = array(
		'plugin.clog.clog_user',
		'plugin.clog.clog_user_meta'
	);
	
	private $_ClogUserMeta;
	
	public function setUp() {
		parent::setUp();
		$this->_ClogUserMeta = ClassRegistry::init('ClogUserMeta');
		$this->_ClogUserMeta->cacheQueries = false;
	}
	
	public function testSingleModel() {
		
		$data = $this->_ClogUserMeta->find('all',array(
			'contain' => false
		));
		
		$this->assertFalse(empty($data));
		$this->assertInternalType('array',$data);
		$this->assertCount(1,$data);
		
	}
	
	public function testSingleModelFirst() {
		
		$data = $this->_ClogUserMeta->find('first',array(
			'contain' => false
		));
		
		$this->assertFalse(empty($data));
		$this->assertInternalType('array',$data);
		$this->assertCount(1,$data);
		
	}
	
	public function testContained() {
		
		$data = $this->_ClogUserMeta->find('first');
		
		$this->assertFalse(empty($data));
		$this->assertInternalType('array',$data);
		$this->assertCount(2,$data);
		$this->assertArrayHasKey('ClogUser',$data);
		$this->assertArrayHasKey('ClogUserMeta',$data);
		$this->assertFalse(empty($data['ClogUser']));
		$this->assertFalse(empty($data['ClogUserMeta']));
		
	}
	
	public function testMetaExists() {
		
		$check = $this->_ClogUserMeta->isMetaExists(1,'key 1');
		
		$this->assertInternalType('boolean',$check);
		$this->assertTrue($check);
		
		$check = $this->_ClogUserMeta->isMetaExists(1,'key 2');
		$this->assertFalse($check);
		
	}
	
	public function testMetaUser() {
		
		$data = $this->_ClogUserMeta->getUserMeta(1);
		
		$this->assertInternalType('array',$data);
		$this->assertFalse(empty($data));
		$this->assertCount(1,$data);
		$this->assertArrayHasKey('ClogUserMeta',$data[0]);
		$this->assertFalse(empty($data[0]['ClogUserMeta']));
		
	}
	
	public function testWrite() {
		
		$data = array(
			'new meta user 1' => 'new meta user 1',
			'new meta user 2' => 'new meta user 2'
		);
		
		$save = $this->_ClogUserMeta->saveMeta(1,$data);
		
		$this->assertInternalType('array',$save);
		$this->assertArrayHasKey('new meta user 1',$save);
		$this->assertArrayHasKey('new meta user 2',$save);
		
	}
	
	public function testWriteDataError() {
		
		$save = $this->_ClogUserMeta->saveMeta(1,array());		
		$this->assertFalse($save);
		
		$save = $this->_ClogUserMeta->saveMeta(1,'test');
		$this->assertFalse($save);
		
	}

	public function testUpdateMeta() {
		
		$update = $this->_ClogUserMeta->updateMeta(1,'key 1','update');
		$data = $this->_ClogUserMeta->find('first',array(
			'contain' => false,
			'conditions' => array('ClogUserMeta.id' => 1),
			'fields' => array('ClogUserMeta.meta_value')
		));
		
		$this->assertInternalType('boolean',$update);
		$this->assertTrue($update);
		$this->assertEqual($data['ClogUserMeta']['meta_value'],'update');
		
		$update = $this->_ClogUserMeta->updateMeta(1,'key unknown','update');
		
		$this->assertInternalType('boolean',$update);
		$this->assertFalse($update);
		
	}
	
	public function testRemove() {
		
		$this->_ClogUserMeta->delete(1,false);
		$total = $this->_ClogUserMeta->find('count');
		
		$this->assertEqual(0,$total);
		
	}
	
}