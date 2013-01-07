<?php

App::uses('CloggyAppModel','Cloggy.Model');
App::uses('CloggyNode','Cloggy.Model');
App::uses('CloggyNodeMeta','Cloggy.Model');

class CloggyNodeMetaTest extends CakeTestCase {
	
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
	private $__CloggyNodeMeta;
	
	public function setUp() {
		parent::setUp();
		$this->__CloggyNode = ClassRegistry::init('CloggyNode');
		$this->__CloggyNodeMeta = ClassRegistry::init('CloggyNodeMeta');
		$this->__CloggyNodeMeta->cacheQueries = false;
		$this->__CloggyNode->cacheQueries = false;
	}
	
	public function testNodeObjects() {
	
		$this->assertFalse(empty($this->__CloggyNode));
		$this->assertFalse(empty($this->__CloggyNodeMeta));
		$this->assertTrue(is_a($this->__CloggyNode,'CloggyNode'));
		$this->assertTrue(is_a($this->__CloggyNodeMeta,'CloggyNodeMeta'));
	
	}
	
	public function testNodeMeta() {
		
		$data = $this->__CloggyNode->find('first',array(
			'contain' => array('CloggyMeta'),
			'conditions' => array('CloggyNode.id' => 4)
		));
		
		$this->assertFalse(empty($data));
		$this->assertArrayHasKey('CloggyMeta',$data);
		$this->assertFalse(empty($data['CloggyMeta']));
		$this->assertCount(2,$data['CloggyMeta']);
		
		$keys = array();
		$values = array();
		
		foreach($data['CloggyMeta'] as $meta) {
			$keys[] = $meta['meta_key'];
			$values[] = $meta['meta_value'];
		}
		
		$this->assertTrue(in_array('test key',$keys));
		$this->assertTrue(in_array('test key 2',$keys));
		$this->assertTrue(in_array('test value',$values));
		$this->assertTrue(in_array('test value 2',$values));
		
	}
	
	public function testGetNodeMeta() {
		
		$data = $this->__CloggyNodeMeta->getNodeMeta(4);
		
		$this->assertFalse(empty($data));
		$this->assertCount(2,$data);		
		
	}
	
	public function testIsMetaExists() {
		
		$check = $this->__CloggyNodeMeta->isMetaExists(4,'test key');
		$checkFake = $this->__CloggyNodeMeta->isMetaExists(4,'test keydfdf');
		
		$this->assertTrue($check);
		$this->assertFalse($checkFake);
		
	}
	
	public function testSaveMeta() {
		
		$metaId = $this->__CloggyNodeMeta->saveMeta(4,array(
			'test_meta_1' => 'test meta 1',
			'test_meta_2' => 'test meta 2'
		));
		
		$this->assertInternalType('array',$metaId);
		$this->assertArrayHasKey('test_meta_1',$metaId);
		$this->assertArrayHasKey('test_meta_2',$metaId);
		$this->assertEqual($metaId['test_meta_1'],3);
		$this->assertEqual($metaId['test_meta_2'],4);
		
		$metaId = $this->__CloggyNodeMeta->saveMeta(4,'testing');
		$this->assertFalse($metaId);
		
	}
	
	public function testUpdateMeta() {
		
		$checkUpdate = $this->__CloggyNodeMeta->updateMeta(4,'test key','testing');
		$data = $this->__CloggyNodeMeta->find('first',array(
			'contain' => false,
			'conditions' => array('CloggyNodeMeta.meta_key' => 'test key'),
			'fields' => array('CloggyNodeMeta.meta_value')
		));
		
		$this->assertFalse(empty($data));
		$this->assertEqual($data['CloggyNodeMeta']['meta_value'],'testing');
		
		$checkUpdateFake = $this->__CloggyNodeMeta->updateMeta(4,'test key2','testing');
		$this->assertFalse($checkUpdateFake);
		
	}
	
	public function testRemoveMeta() {
		
		$this->__CloggyNodeMeta->delete(1,false);
		$data = $this->__CloggyNodeMeta->find('first',array(
			'contain' => false,
			'conditions' => array('CloggyNodeMeta.id' => 1),			
		));
		
		$this->assertTrue(empty($data));
		
	}
	
}