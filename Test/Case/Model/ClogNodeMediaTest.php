<?php

App::uses('ClogAppModel','Clog.Model');
App::uses('ClogNodeMedia', 'Clog.Model');

class ClogNodeMediaTest extends CakeTestCase {
	
	public $fixtures = array(		
		'plugin.clog.clog_node',		
		'plugin.clog.clog_node_media'		
	);
	
	private $_ClogMedia;
	
	public function setUp() {
		parent::setUp();
		$this->_ClogMedia = ClassRegistry::init('ClogNodeMedia');
		$this->_ClogMedia->cacheQueries = false;
	}
	
	public function testSingleModel() {
		
		$data = $this->_ClogMedia->find('all',array(
			'contain' => false
		));
		
		$this->assertInternalType('array',$data);
		$this->assertFalse(empty($data));
		$this->assertCount(1,$data);
		
	}
	
	public function testSingleModelFirst() {
		
		$data = $this->_ClogMedia->find('first',array(
			'contain' => false
		));
		
		$this->assertInternalType('array',$data);
		$this->assertFalse(empty($data));
		$this->assertCount(1,$data);
		$this->assertArrayHasKey('ClogNodeMedia',$data);
		
	}
	
	public function testContained() {
		
		$data = $this->_ClogMedia->find('all');
		
		$this->assertInternalType('array',$data);
		$this->assertFalse(empty($data));
		$this->assertCount(1,$data);
		$this->assertArrayHasKey('ClogNodeMedia',$data[0]);
		$this->assertArrayHasKey('ClogNode',$data[0]);
		$this->assertEqual($data[0]['ClogNode']['id'],'3');
		$this->assertEqual($data[0]['ClogNodeMedia']['node_id'],$data[0]['ClogNode']['id']);
		
	}
	
	public function testContainedFirst() {
		
		$data = $this->_ClogMedia->find('first');
		
		$this->assertInternalType('array',$data);
		$this->assertFalse(empty($data));
		$this->assertCount(2,$data);
		$this->assertArrayHasKey('ClogNodeMedia',$data);
		$this->assertArrayHasKey('ClogNode',$data);
		$this->assertEqual($data['ClogNode']['id'],'3');
		$this->assertEqual($data['ClogNodeMedia']['node_id'],$data['ClogNode']['id']);
		
	}
	
	public function testWrite() {
		
		$this->_ClogMedia->create();
		$this->_ClogMedia->save(array(
			'ClogNodeMedia' => array(
				'node_id' => 1,
				'media_file_type' => 'video',
				'media_file_location' => 'http://youtube.com'
			)
		));
		
		$insertId = $this->_ClogMedia->id;
		$data = $this->_ClogMedia->find('first',array(
			'conditions' => array('ClogNodeMedia.id' => $insertId)
		));
		
		$this->assertInternalType('array',$data);
		$this->assertFalse(empty($data));
		$this->assertCount(2,$data);
		$this->assertEqual($data['ClogNodeMedia']['media_file_location'],'http://youtube.com');
		
	}
	
	public function testRemove() {
		
		$this->_ClogMedia->create();
		$this->_ClogMedia->save(array(
			'ClogNodeMedia' => array(
				'node_id' => 1,
				'media_file_type' => 'video',
				'media_file_location' => 'http://youtube.com'
			)
		));
		
		$insertId = $this->_ClogMedia->id;
		$data = $this->_ClogMedia->find('first',array(
			'conditions' => array('ClogNodeMedia.id' => $insertId)
		));
		
		$this->assertInternalType('array',$data);
		$this->assertFalse(empty($data));
		$this->assertCount(2,$data);
		$this->assertEqual($data['ClogNodeMedia']['media_file_location'],'http://youtube.com');
		
		$this->_ClogMedia->delete($insertId,false);
		$data = $this->_ClogMedia->find('first',array(
			'conditions' => array('ClogNodeMedia.id' => $insertId)
		));
		
		$this->assertTrue(empty($data));
		
	}
	
}