<?php

App::uses('CloggyAppModel','Cloggy.Model');
App::uses('CloggyNode','Cloggy.Model');
App::uses('CloggyNodePermalink','Cloggy.Model');

class CloggyNodePermalinkTest extends CakeTestCase {
	
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
	private $__CloggyNodePermalink;
	
	public function setUp() {
		parent::setUp();
		$this->__CloggyNode = ClassRegistry::init('CloggyNode');
		$this->__CloggyNodePermalink = ClassRegistry::init('CloggyNodePermalink');
		$this->__CloggyNodePermalink->cacheQueries = false;
		$this->__CloggyNode->cacheQueries = false;
	}
	
	public function testNodeObjects() {
	
		$this->assertFalse(empty($this->__CloggyNode));
		$this->assertFalse(empty($this->__CloggyNodePermalink));
		$this->assertTrue(is_a($this->__CloggyNode,'CloggyNode'));
		$this->assertTrue(is_a($this->__CloggyNodePermalink,'CloggyNodePermalink'));
	
	}
	
	public function testNodePermalink() {
		
		$data = $this->__CloggyNode->find('first',array(
			'contain' => array('CloggyPermalink'),
			'conditions' => array('CloggyNode.id' => 1)
		));
		
		$this->assertFalse(empty($data));
		$this->assertArrayHasKey('CloggyPermalink',$data);
		$this->assertFalse(empty($data['CloggyPermalink']));
		$this->assertArrayHasKey('permalink_url',$data['CloggyPermalink']);
		$this->assertEqual('test-subject-has-content',$data['CloggyPermalink']['permalink_url']);
		
	}
	
	public function testCreatePermalink() {
		
		$dataSubject = $this->__CloggyNode->find('first',array(
			'contain' => array(
				'CloggySubject' => array(
					'fields' => array('CloggySubject.subject')
				)
			),
			'conditions' => array('CloggyNode.id' => 2)			
		));
		
		$this->assertFalse(empty($dataSubject));
		$this->assertArrayHasKey('CloggySubject',$dataSubject);
		$this->assertFalse(empty($dataSubject['CloggySubject']));
		$this->assertArrayHasKey('subject',$dataSubject['CloggySubject']);
		$this->assertFalse(empty($dataSubject['CloggySubject']['subject']));
		
		$subject = $dataSubject['CloggySubject']['subject'];
		$nodeId = $dataSubject['CloggyNode']['id'];
		
		$permalinkId = $this->__CloggyNodePermalink->createPermalink($nodeId,$subject,'-');
		$this->assertEqual(2,$permalinkId);
		
		$dataPermalink = $this->__CloggyNodePermalink->find('first',array(
			'contain' => false,
			'conditions' => array('CloggyNodePermalink.id' => $permalinkId)
		));
		
		$this->assertFalse(empty($dataPermalink));
		$this->assertEqual($dataPermalink['CloggyNodePermalink']['permalink_url'],'test-subject-term');
		
	}
	
	public function testRemovePermalink() {
		
		$this->__CloggyNodePermalink->delete(1,false);
		$data = $this->__CloggyNodePermalink->find('first',array(
			'contain' => false,
			'conditions' => array('CloggyNodePermalink.id' => 1)
		));
		
		$this->assertTrue(empty($data));
		
	}
	
}