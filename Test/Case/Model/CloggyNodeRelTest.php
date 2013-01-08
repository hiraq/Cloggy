<?php

App::uses('CloggyAppModel','Cloggy.Model');
App::uses('CloggyNode','Cloggy.Model');
App::uses('CloggyNodeRel','Cloggy.Model');

class CloggyNodeRelTest extends CakeTestCase {
	
	public $fixtures = array(
		'plugin.cloggy.cloggy_node',
		'plugin.cloggy.cloggy_node_type',
		'plugin.cloggy.cloggy_node_subject',
		'plugin.cloggy.cloggy_node_content',
		'plugin.cloggy.cloggy_node_permalink',
		'plugin.cloggy.cloggy_node_media',
		'plugin.cloggy.cloggy_node_meta',
		'plugin.cloggy.cloggy_node_rel',
		'plugin.cloggy.cloggy_user'
	);
	
	private $__CloggyNode;
	private $__CloggyNodeRel;
	
	public function setUp() {
		parent::setUp();
		$this->__CloggyNode = ClassRegistry::init('CloggyNode');
		$this->__CloggyNodeRel = ClassRegistry::init('CloggyNodeRel');
		$this->__CloggyNodeRel->cacheQueries = false;
		$this->__CloggyNode->cacheQueries = false;
	}
	
	public function testNodeObjects() {
	
		$this->assertFalse(empty($this->__CloggyNode));
		$this->assertFalse(empty($this->__CloggyNodeRel));
		$this->assertTrue(is_a($this->__CloggyNode,'CloggyNode'));
		$this->assertTrue(is_a($this->__CloggyNodeRel,'CloggyNodeRel'));
	
	}
	
	public function testNodeRel() {
		
		$data = $this->__CloggyNodeRel->find('first',array(
			'contain' => array(
				'CloggyNode',
				'CloggyNodeObject'
			),
			'conditions' => array('CloggyNodeRel.id' => 1)
		));
		
		$this->assertFalse(empty($data));
		$this->assertArrayHasKey('CloggyNode',$data);
		$this->assertArrayHasKey('CloggyNodeObject',$data);
		$this->assertEqual($data['CloggyNode']['id'],2);
		$this->assertEqual($data['CloggyNodeObject']['id'],1);
		
	}
	
	public function testCheckRelationExists() {
		
		$check = $this->__CloggyNodeRel->isRelationExists(2,1,'test rels');
		$checkFake = $this->__CloggyNodeRel->isRelationExists(2,2,'test rels');
		
		$this->assertTrue($check);
		$this->assertFalse($checkFake);
		
		
	}
	
	public function testDeleteAllRelations() {
		
		$this->__CloggyNodeRel->deleteAllRelations(1,'test rels');
		$data = $this->__CloggyNodeRel->find('first',array(
			'contain' => false,
			'conditions' => array('CloggyNodeRel.node_object_id' => 1)
		));
		
		$this->assertTrue(empty($data));
		
	}
	
	public function testSaveRelations() {
		
		$relId = $this->__CloggyNodeRel->saveRelation(1,2,'test relation 2');
		$relIdFake = $this->__CloggyNodeRel->saveRelation(2,1,'test rels');
		
		$this->assertEqual($relId,2);
		$this->assertFalse($relIdFake);
		
	}
	
}