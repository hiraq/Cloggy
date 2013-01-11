<?php

App::uses('CloggyAppModel','Cloggy.Model');
App::uses('CloggyNode','Cloggy.Model');
App::uses('CloggyNodeSubject','Cloggy.Model');

class CloggyNodeSubjectTest extends CakeTestCase {
	
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
	private $__CloggyNodeSubject;
	
	public function setUp() {
		parent::setUp();
		$this->__CloggyNode = ClassRegistry::init('CloggyNode');
		$this->__CloggyNodeSubject = ClassRegistry::init('CloggyNodeSubject');
		$this->__CloggyNodeSubject->cacheQueries = false;
		$this->__CloggyNode->cacheQueries = false;
	}
	
	public function testNodeObjects() {
	
		$this->assertFalse(empty($this->__CloggyNode));
		$this->assertFalse(empty($this->__CloggyNodeSubject));
		$this->assertTrue(is_a($this->__CloggyNode,'CloggyNode'));
		$this->assertTrue(is_a($this->__CloggyNodeSubject,'CloggyNodeSubject'));
	
	}
	
	public function testNodeSubject() {
	
		$data = $this->__CloggyNode->find('first',array(
			'contain' => array('CloggySubject'),
			'conditions' => array('CloggyNode.id' => 1)
		));
	
		$this->assertFalse(empty($data));
		$this->assertArrayHasKey('CloggyNode',$data);
		$this->assertArrayHasKey('CloggySubject',$data);
		$this->assertFalse(empty($data['CloggySubject']));
		$this->assertFalse(empty($data['CloggySubject']['subject']));
		$this->assertEqual($data['CloggySubject']['subject'],'test subject has content');
		
		$data = $this->__CloggyNode->find('first',array(
			'contain' => array('CloggySubject'),
			'conditions' => array('CloggyNode.id' => 2)
		));
		
		$this->assertFalse(empty($data));
		$this->assertArrayHasKey('CloggyNode',$data);
		$this->assertArrayHasKey('CloggySubject',$data);
		$this->assertFalse(empty($data['CloggySubject']));
		$this->assertFalse(empty($data['CloggySubject']['subject']));
		$this->assertEqual($data['CloggySubject']['subject'],'test subject term');
	
	}
	
	public function testNodeSubjectCreate() {
	
		$nodeId = $this->__CloggyNode->generateEmptyNode(1,1);
		$this->__CloggyNode->modifyNode($nodeId,array(
			'has_subject' => 1,
			'node_type_id' => 2
		));
		
		$subjectId = $this->__CloggyNodeSubject->createSubject($nodeId,'test create subject');		
		$this->assertEqual(3,$subjectId);
		
		$data = $this->__CloggyNodeSubject->find('first',array(
			'contain' => false,
			'conditions' => array('CloggyNodeSubject.id' => $subjectId),
			'fields' => array('CloggyNodeSubject.subject')
		));
		
		$this->assertFalse(empty($data));
		$this->assertEqual($data['CloggyNodeSubject']['subject'],'test create subject');
	
	}
	
	public function testRemoveSubject() {
		
		$this->__CloggyNodeSubject->delete(1,false);
		$data = $this->__CloggyNodeSubject->find('first',array(
			'contain' => false,
			'conditions' => array('CloggyNodeSubject.id' => 1)
		));
		
		$this->assertTrue(empty($data));
		
	}
	
}