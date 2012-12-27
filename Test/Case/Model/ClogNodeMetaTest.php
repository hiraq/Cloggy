<?php

App::uses('ClogAppModel','Clog.Model');
App::uses('ClogNodeMeta', 'Clog.Model');

class ClogNodeMetaTest extends CakeTestCase {
	
	public $fixtures = array(		
		'plugin.clog.clog_node',		
		'plugin.clog.clog_node_meta'		
	);
	
	private $_ClogMeta;
	
	public function setUp() {
		parent::setUp();
		$this->_ClogMeta = ClassRegistry::init('ClogNodeMeta');
		$this->_ClogMeta->cacheQueries = false;
	}
	
	public function testSingleModel() {
		
		$data = $this->_ClogMeta->find('all',array(
			'contain' => false
		));
		
		$this->assertFalse(empty($data));
		$this->assertInternalType('array',$data);
		$this->assertCount(2,$data);
		
	}
	
	public function testSingleModelFirst() {
		
		$data = $this->_ClogMeta->find('first',array(
			'contain' => false
		));
		
		$this->assertFalse(empty($data));
		$this->assertInternalType('array',$data);
		$this->assertCount(1,$data);
		
	}
	
	public function testContained() {
		
		$data = $this->_ClogMeta->find('all');
		
		$this->assertFalse(empty($data));
		$this->assertInternalType('array',$data);
		$this->assertCount(2,$data);
		$this->assertArrayHasKey('ClogNode',$data[0]);
		$this->assertArrayHasKey('ClogNodeMeta',$data[0]);
		$this->assertFalse(empty($data[0]['ClogNode']));
		$this->assertFalse(empty($data[1]['ClogNode']));
		
	}
	
	public function testContainedFirst() {
		
		$data = $this->_ClogMeta->find('first');
		
		$this->assertFalse(empty($data));
		$this->assertInternalType('array',$data);
		$this->assertCount(2,$data);
		$this->assertArrayHasKey('ClogNode',$data);
		$this->assertArrayHasKey('ClogNodeMeta',$data);
		$this->assertFalse(empty($data['ClogNode']));		
		
	}
	
	public function testExists() {
		
		$data = $this->_ClogMeta->isMetaExists(4,'test meta');
		
		$this->assertInternalType('boolean',$data);
		$this->assertTrue($data);		
		
	}
	
	public function testWrite() {
		
		$data = array(
			'test new meta 1' => 'test new value 1',
			'test new meta 2' => 'test new value 2'
		);
		
		$save = $this->_ClogMeta->saveMeta(1,$data);
				
		$this->assertInternalType('array',$save);
		$this->assertCount(2,$save);
		$this->assertArrayHasKey('test new meta 1',$save);
		$this->assertArrayHasKey('test new meta 2',$save);
		
		$data = $this->_ClogMeta->find('count');
		$this->assertEqual($data,4);
		
	}
	
	public function testWriteDataError() {
		
		$save = $this->_ClogMeta->saveMeta(1,array());
		
		$this->assertInternalType('boolean',$save);
		$this->assertFalse($save);
		
		$save = $this->_ClogMeta->saveMeta(1,'test');
		
		$this->assertInternalType('boolean',$save);
		$this->assertFalse($save);
		
	}
	
	public function testNodeMeta() {
		
		$data = $this->_ClogMeta->getNodeMeta(4);
		
		$this->assertInternalType('array',$data);
		$this->assertFalse(empty($data));
		$this->assertCount(2,$data);
		
	}
	
	public function testUpdateMeta() {
		
		$update = $this->_ClogMeta->updateMeta(4,'test meta','update');
		
		$data = $this->_ClogMeta->find('first',array(
			'contain' => false,
			'conditions' => array('ClogNodeMeta.node_id' => 4,'ClogNodeMeta.meta_key' => 'test meta'),
			'fields' => array('ClogNodeMeta.meta_value')
		));
		
		$this->assertInternalType('boolean',$update);
		$this->assertTrue($update);
		$this->assertFalse(empty($data));
		$this->assertArrayHasKey('meta_value',$data['ClogNodeMeta']);
		$this->assertEqual($data['ClogNodeMeta']['meta_value'],'update');
		
		$update = $this->_ClogMeta->updateMeta(4,'test meta unknown','update');
		
		$this->assertInternalType('boolean',$update);
		$this->assertFalse($update);
		
	}
	
	public function testRemove() {
		
		$this->_ClogMeta->deleteAll(array(
			'ClogNodeMeta.node_id' => 4
		),false);
		
		$data = $this->_ClogMeta->find('count');
		$this->assertEqual($data,0);
		
	}
	
}