<?php

App::uses('ClogAppModel','Clog.Model');
App::uses('ClogNodeContent', 'Clog.Model');

class ClogNodeContentTest extends CakeTestCase {
	
	public $fixtures = array(
		'plugin.clog.clog_node',
		'plugin.clog.clog_node_content'
	);
	
	private $_ClogNodeContent;
	
	public function setUp() {
		parent::setUp();
		$this->_ClogNodeContent = ClassRegistry::init('ClogNodeContent');
		$this->_ClogNodeContent->cacheQueries = false;
	}
	
	public function testSingle() {
		
		$data = $this->_ClogNodeContent->find('all',array(
			'contain' => false			
		));
		
		$this->assertInternalType('array',$data);
		$this->assertTrue(!empty($data));
		$this->assertCount(3,$data);
		
	}
	
	public function testSingleFirst() {
		
		$data = $this->_ClogNodeContent->find('first',array(
			'contain' => false
		));
		
		$this->assertInternalType('array',$data);
		$this->assertTrue(!empty($data));
		$this->assertArrayHasKey('ClogNodeContent',$data);
		$this->assertEqual($data['ClogNodeContent']['content'],'test content 1');
		
	}
	
	public function testContainedAll() {
		
		$data = $this->_ClogNodeContent->find('all');
		
		$this->assertInternalType('array',$data);
		$this->assertTrue(!empty($data));
		$this->assertCount(3,$data);
		
	}
	
	public function testContainedFirst() {
		
		$data = $this->_ClogNodeContent->find();
		
		$this->assertInternalType('array',$data);
		$this->assertTrue(!empty($data));
		$this->assertCount(2,$data);
		$this->assertArrayHasKey('ClogNodeContent',$data);
		$this->assertArrayHasKey('ClogNode',$data);
		$this->assertEqual($data['ClogNodeContent']['node_id'],$data['ClogNode']['id']);
		
	}
	
	public function testWrite() {
		
		$this->_ClogNodeContent->create();
		$this->_ClogNodeContent->save(array(
			'ClogNodeContent' => array(
				'node_id' => 1,
				'content' => 'test write content'
			)
		));
		
		$data = $this->_ClogNodeContent->find('first',array(
			'conditions' => array('ClogNodeContent.content' => 'test write content')		
		));
		
		$this->assertFalse(empty($this->_ClogNodeContent->id));
		$this->assertFalse(empty($data));
		$this->assertEqual($this->_ClogNodeContent->id,4);
		
	}
	
	public function testRemove() {
		
		$this->_ClogNodeContent->create();
		$this->_ClogNodeContent->save(array(
			'ClogNodeContent' => array(
					'node_id' => 1,
					'content' => 'test write content'
			)
		));
		
		$data = $this->_ClogNodeContent->find('first',array(
			'conditions' => array('ClogNodeContent.content' => 'test write content')
		));
		
		$this->assertFalse(empty($this->_ClogNodeContent->id));
		$this->assertFalse(empty($data));
		$this->assertEqual($this->_ClogNodeContent->id,4);		
				
		
		$this->_ClogNodeContent->delete($this->_ClogNodeContent->id,false);
		
		$data = $this->_ClogNodeContent->find('all',array('contain' => false));
		$this->assertCount(3,$data);
		
		$data = $this->_ClogNodeContent->find('all',array(
			'contain' => false,
			'conditions' => array('ClogNodeContent.id' => 4)
		));
		
		$this->assertTrue(empty($data));
		
	}
	
}