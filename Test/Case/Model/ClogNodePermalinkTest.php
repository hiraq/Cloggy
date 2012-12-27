<?php

App::uses('ClogAppModel','Clog.Model');
App::uses('ClogNodePermalink', 'Clog.Model');

class ClogNodePermalinkTest extends CakeTestCase {
	
	public $fixtures = array(
		'plugin.clog.clog_node',
		'plugin.clog.clog_node_permalink',
	);
	
	private $_ClogPermalink;
	
	public function setUp() {
		
		parent::setUp();		
		$this->_ClogPermalink = ClassRegistry::init('ClogNodePermalink');
		$this->_ClogPermalink->cacheQueries = false;
		
	}
	
	public function testSingleModel() {
		
		$data = $this->_ClogPermalink->find('all',array(
			'contain' => false
		));
		
		$this->assertFalse(empty($data));
		$this->assertCount(3,$data);
		$this->assertInternalType('array',$data);
		
	}
	
	public function testSingleModelFirst() {
		
		$data = $this->_ClogPermalink->find('first',array(
			'contain' => false
		));
		
		$this->assertFalse(empty($data));
		$this->assertCount(1,$data);
		$this->assertInternalType('array',$data);
		$this->assertEqual($data['ClogNodePermalink']['id'],1);
		
	}
	
	public function testContainedAll() {
		
		$data = $this->_ClogPermalink->find('all');
		
		$this->assertFalse(empty($data));
		$this->assertCount(3,$data);
		$this->assertInternalType('array',$data);
		
		$associated = $this->_getAssociatedModels();
		$check = array();
		
		foreach($data as $rec) {
			if(is_array($rec)) {
				foreach($rec as $key => $value) {
					if(in_array($key,$associated)) {
						$check[] = $key;
					}
				}
			}
		}
		
		$this->assertFalse(empty($check));
		$this->assertCount(3,$check);
		
	}
	
	public function testContainedFirst() {
		
		$data = $this->_ClogPermalink->find('first',array(
			'conditions' => array('ClogNodePermalink.id' => 1)
		));
		
		$this->assertFalse(empty($data));
		$this->assertCount(2,$data);
		$this->assertInternalType('array',$data);
		
	}
	
	public function testWrite() {
		
		$this->_ClogPermalink->create();
		$this->_ClogPermalink->save(array(
			'ClogNodePermalink' => array(				
				'node_id' => 1,
				'permalink_url' => Inflector::slug('test write permalink')
			)		
		));
		
		$data = $this->_ClogPermalink->find('first',array(
			'contain' => false,
			'conditions' => array('ClogNodePermalink.id' => 4)
		));
		
		$this->assertFalse(empty($data));
		$this->assertCount(1,$data);
		$this->assertInternalType('array',$data);
		$this->assertEqual($this->_ClogPermalink->id,4);
		$this->assertEqual($data['ClogNodePermalink']['permalink_url'],Inflector::slug('test write permalink'));
		
	}
	
	public function testRemove() {
		
		$this->_ClogPermalink->create();
		$this->_ClogPermalink->save(array(
			'ClogNodePermalink' => array(
					'node_id' => 1,
					'permalink_url' => Inflector::slug('test write permalink')
			)
		));
		
		$data = $this->_ClogPermalink->find('first',array(
			'contain' => false,
			'conditions' => array('ClogNodePermalink.id' => 4)
		));
		
		$dataAll = $this->_ClogPermalink->find('count');
				
		$this->assertFalse(empty($data));
		$this->assertCount(1,$data);
		$this->assertInternalType('array',$data);
		$this->assertEqual($this->_ClogPermalink->id,4);
		$this->assertEqual($data['ClogNodePermalink']['permalink_url'],Inflector::slug('test write permalink'));
		$this->assertEqual($dataAll,4);
		
		$this->_ClogPermalink->delete($this->_ClogPermalink->id,false);
		
		$dataAfterRemove = $this->_ClogPermalink->find('all');
		$data = $this->_ClogPermalink->find('first',array(
			'contain' => false,
			'conditions' => array('ClogNodePermalink.id' => 4)
		));
		
		$this->assertCount(3,$dataAfterRemove);
		$this->assertTrue(empty($data));
		
	}
	
	private function _getAssociatedModels() {
		return array('ClogNode');
	}
	
}