<?php

App::uses('ClogAppModel','Clog.Model');
App::uses('ClogNodeSubject', 'Clog.Model');

class ClogNodeSubjectTest extends CakeTestCase {
	
	public $fixtures = array(		
		'plugin.clog.clog_node',
		'plugin.clog.clog_node_subject',					
	);
	
	private $_ClogSubject;
	
	public function setUp() {
		parent::setUp();
		$this->_ClogSubject = ClassRegistry::init('ClogNodeSubject');
		$this->_ClogSubject->cacheQueries = false;
	}
	
	public function testSingleModel() {
		
		$data = $this->_ClogSubject->find('all',array(
				'contain' => false
		));
		
		$this->assertFalse(empty($data));
		$this->assertInternalType('array',$data);
		$this->assertCount(3,$data);
		
	}
	
	public function testSingleModelFirst() {
		
		$data = $this->_ClogSubject->find('first',array(
				'contain' => false
		));
		
		$this->assertFalse(empty($data));
		$this->assertInternalType('array',$data);
		$this->assertCount(1,$data);
		$this->assertEqual($data['ClogNodeSubject']['subject'],'test subject');
		
	}
	
	public function testContainedFirst() {
		
		$data = $this->_ClogSubject->find('first',array(
				'conditions' => array('ClogNodeSubject.id' => 1)
		));
		
		$this->assertFalse(empty($data));
		$this->assertInternalType('array',$data);
		$this->assertCount(2,$data);
		$this->assertArrayHasKey('ClogNode',$data);
		$this->assertArrayHasKey('has_subject',$data['ClogNode']);
		$this->assertEqual($data['ClogNode']['has_subject'],1);
		
	}
	
	public function testContainedAll() {
		
		$data = $this->_ClogSubject->find('all');
		
		$this->assertFalse(empty($data));
		$this->assertInternalType('array',$data);
		$this->assertCount(3,$data);
		
	}
	
	public function testWrite() {
		
		$this->_ClogSubject->create();
		$this->_ClogSubject->save(array(
			'ClogNodeSubject' => array(
				'node_id' => 2,
				'subject' => 'test subject write'
			)
		));
		
		$data = $this->_ClogSubject->find('first',array(
				'contain' => false,
				'conditions' => array('ClogNodeSubject.id' => 4)
		));
		
		$this->assertTrue(!empty($this->_ClogSubject->id));
		$this->assertEqual($this->_ClogSubject->id,4);
		$this->assertEqual($data['ClogNodeSubject']['id'],4);
		
	}
	
	public function testRemove() {
		
		$this->_ClogSubject->create();
		$this->_ClogSubject->save(array(
				'ClogNodeSubject' => array(
						'node_id' => 2,
						'subject' => 'test subject write'
				)
		));
		
		$data = $this->_ClogSubject->find('first',array(
				'contain' => false,
				'conditions' => array('ClogNodeSubject.id' => 4)
		));
		
		$this->assertTrue(!empty($this->_ClogSubject->id));
		$this->assertEqual($this->_ClogSubject->id,4);
		$this->assertEqual($data['ClogNodeSubject']['id'],4);
		
		$this->_ClogSubject->delete($this->_ClogSubject->id,false);
		
		$dataAfterRemove = $this->_ClogSubject->find('all');
		
		$this->assertTrue(empty($this->_ClogSubject->id));
		$this->assertCount(3,$dataAfterRemove);
		
	}
	
}