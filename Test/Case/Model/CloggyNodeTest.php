<?php

App::uses('CloggyAppModel','Cloggy.Model');
App::uses('CloggyNode','Cloggy.Model');

class CloggyNodeTest extends CakeTestCase {
	
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

	public function setUp() {
		
		parent::setUp();
		$this->__CloggyNode = ClassRegistry::init('CloggyNode');
		
	}
	
	public function testModelFindUnContainable() {
		
		$data = $this->__CloggyNode->find('first',array(
			'contain' => false
		));
		
		$this->assertFalse(empty($data));
		$this->assertArrayHasKey('CloggyNode',$data);
		$this->assertArrayHasKey('id',$data['CloggyNode']);
		$this->assertEqual(1,$data['CloggyNode']['id']);
		
	}
	
	public function testModelFindContainable() {
		
		$data = $this->__CloggyNode->find('first');
				
		$this->assertFalse(empty($data));
		$this->assertArrayHasKey('CloggyNode',$data);
		$this->assertArrayHasKey('CloggyType',$data);
		
	}
	
}