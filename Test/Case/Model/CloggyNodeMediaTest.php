<?php

App::uses('CloggyAppModel','Cloggy.Model');
App::uses('CloggyNode','Cloggy.Model');
App::uses('CloggyNodeMedia','Cloggy.Model');

class CloggyNodeMediaTest extends CakeTestCase {
	
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
	private $__CloggyNodeMedia;
	
	public function setUp() {
		parent::setUp();
		$this->__CloggyNode = ClassRegistry::init('CloggyNode');
		$this->__CloggyNodeMedia = ClassRegistry::init('CloggyNodeMedia');
		$this->__CloggyNodeMedia->cacheQueries = false;
		$this->__CloggyNode->cacheQueries = false;
	}
	
	public function testNodeObjects() {
	
		$this->assertFalse(empty($this->__CloggyNode));
		$this->assertFalse(empty($this->__CloggyNodeMedia));
		$this->assertTrue(is_a($this->__CloggyNode,'CloggyNode'));
		$this->assertTrue(is_a($this->__CloggyNodeMedia,'CloggyNodeMedia'));
	
	}
	
	public function testNodeMedia() {
		
		$data = $this->__CloggyNode->find('first',array(
			'contain' => array('CloggyMedia'),
			'conditions' => array('CloggyNode.id' => 3)
		));
		
		$this->assertFalse(empty($data));
		$this->assertArrayHasKey('CloggyNode',$data);
		$this->assertArrayHasKey('CloggyMedia',$data);
		$this->assertFalse(empty($data['CloggyMedia']));
		$this->assertFalse(empty($data['CloggyMedia']['media_file_type']));
		$this->assertEqual($data['CloggyMedia']['media_file_type'],'image/jpeg');
		$this->assertEqual($data['CloggyMedia']['media_file_location'],'/upload/test_file.jpg');
		
	}
	
	public function testMediaCreate() {
		
		$nodeId = $this->__CloggyNode->generateEmptyNode(1,1);
		$this->__CloggyNode->modifyNode($nodeId,array(
			'has_content' => 1
		));
		
		$this->assertEqual($nodeId,5);
		
		$this->__CloggyNodeMedia->saveMedia($nodeId,'video','http://youtube.com');
		
		$data = $this->__CloggyNode->find('first',array(
			'contain' => array('CloggyMedia'),
			'conditions' => array('CloggyNode.id' => $nodeId)
		));
		
		$this->assertFalse(empty($data));
		$this->assertArrayHasKey('CloggyNode',$data);
		$this->assertArrayHasKey('CloggyMedia',$data);
		$this->assertFalse(empty($data['CloggyMedia']));
		$this->assertFalse(empty($data['CloggyMedia']['media_file_type']));
		$this->assertEqual($data['CloggyMedia']['media_file_type'],'video');
		$this->assertEqual($data['CloggyMedia']['media_file_location'],'http://youtube.com');
		
	}
	
	public function testRemoveMedia() {
		
		$nodeId = $this->__CloggyNode->generateEmptyNode(1,1);
		$this->__CloggyNode->modifyNode($nodeId,array(
			'has_content' => 1
		));
		
		$this->assertEqual($nodeId,5);
		
		$this->__CloggyNodeMedia->saveMedia($nodeId,'video','http://youtube.com');
		
		$this->__CloggyNode->delete($nodeId,false);
		$this->__CloggyNodeMedia->deleteAll(array(
			'CloggyNodeMedia.node_id' => $nodeId
		),false);
		
		$data = $this->__CloggyNodeMedia->find('first',array(
			'contain' => false,
			'conditions' => array('CloggyNodeMedia.node_id' => $nodeId)
		));
		
		$dataNode = $this->__CloggyNode->find('first',array(
			'contain' => false,
			'conditions' => array('CloggyNode.id' => $nodeId)
		));
		
		$this->assertTrue(empty($data));
		$this->assertTrue(empty($dataNode));
		
	}
	
}