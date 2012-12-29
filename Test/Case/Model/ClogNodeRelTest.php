<?php

App::uses('ClogAppModel','Clog.Model');
App::uses('ClogNodeRel', 'Clog.Model');

class ClogNodeRelTest extends CakeTestCase {
	
	public $fixtures = array(		
		'plugin.clog.clog_node',		
		'plugin.clog.clog_node_rel'
	);
	
	private $_ClogRel;
	
	public function setUp() {
		parent::setUp();
		$this->_ClogRel = ClassRegistry::init('ClogNodeRel');
		$this->_ClogRel->cacheQueries = false;
	}
	
	public function testSingleModel() {
		
		$data = $this->_ClogRel->find('all',array(
			'contain' => false
		));
		
		$this->assertFalse(empty($data));
		$this->assertInternalType('array',$data);
		$this->assertCount(1,$data);
		
	}
	
	public function testSingleModelFirst() {
		
		$data = $this->_ClogRel->find('first',array(
			'contain' => false
		));
		
		$this->assertFalse(empty($data));
		$this->assertInternalType('array',$data);
		$this->assertCount(1,$data);
		
	}
	
	public function testContained() {
		
		$data = $this->_ClogRel->find('first');
		
		$this->assertFalse(empty($data));
		$this->assertInternalType('array',$data);		
		$this->assertCount(3,$data);
		$this->assertArrayHasKey('ClogNode',$data);
		$this->assertArrayHasKey('ClogNodeObject',$data);
		$this->assertArrayHasKey('ClogNodeRel',$data);
		$this->assertEqual($data['ClogNode']['id'],$data['ClogNodeRel']['node_id']);
		$this->assertEqual($data['ClogNodeObject']['id'],$data['ClogNodeRel']['node_object_id']);
		$this->assertEqual($data['ClogNodeRel']['relation_name'],'test relation');
		
	}
	
	public function testExists() {
		
		$check = $this->_ClogRel->isRelationExists(1,4,'test relation');
		
		$this->assertInternalType('boolean',$check);
		$this->assertTrue($check);
		
	}
	
	public function testWrite() {
		
		$save = $this->_ClogRel->saveRelation(1,2,'test new relation');

		$this->assertFalse(empty($save));
		$this->assertEqual($save,2);
		
		$data = $this->_ClogRel->find('first',array(
			'conditions' => array(
				'ClogNodeRel.id' => 2				
			)
		));
		
		$this->assertFalse(empty($data));
		$this->assertEqual($data['ClogNodeRel']['relation_name'],'test new relation');
		$this->assertEqual($data['ClogNode']['id'],$data['ClogNodeRel']['node_id']);
		$this->assertEqual($data['ClogNodeObject']['id'],$data['ClogNodeRel']['node_object_id']);
		
	}
	
	public function testWriteErrorData() {
		
		$save = $this->_ClogRel->saveRelation(1,4,'test relation');
		
		$this->assertInternalType('boolean',$save);
		$this->assertFalse($save);
		
	}
	
	public function testRemove() {
		
		$this->_ClogRel->deleteAll(array(
			'ClogNodeRel.node_id' => 1
		),false);
		
		$data = $this->_ClogRel->find('count');
		$this->assertEqual($data,0);
		
	}
	
	public function testRemoveAll() {
		
		$save = $this->_ClogRel->saveRelation(1,4,'test relation');						
		$this->_ClogRel->deleteAllRelations(4,'test relation');	
		
		$data = $this->_ClogRel->find('count',array(
			'contain' => false,
			'conditions' => array('ClogNodeRel.node_object_id' => 4)
		));
		
		$this->assertEqual($data,0);
		
	}
	
}