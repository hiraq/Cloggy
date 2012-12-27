<?php

App::uses('ClogAppModel','Clog.Model');
App::uses('ClogNode', 'Clog.Model');

class ClogNodeTest extends CakeTestCase {
	
	public $fixtures = array(
		'plugin.clog.clog_node_type',
		'plugin.clog.clog_user',
		'plugin.clog.clog_node',
		'plugin.clog.clog_node_subject',
		'plugin.clog.clog_node_content',
		'plugin.clog.clog_node_permalink',
		'plugin.clog.clog_node_media',
		'plugin.clog.clog_node_meta',
		'plugin.clog.clog_node_rel',		
	);
	
	private $_ClogNode;
	
	public function setUp() {
		parent::setUp();
		$this->_ClogNode = ClassRegistry::init('ClogNode');
		$this->_ClogNode->cacheQueries = false;
	}		
	
	public function testSingleModel() {
		
		$data = $this->_ClogNode->find('all',array(
			'contain' => false
		));
		
		$this->assertFalse(empty($data));
		$this->assertInternalType('array',$data);
		$this->assertCount(4,$data);
		
	}
	
	public function testSingleModelFirst() {
		
		$data = $this->_ClogNode->find('first',array(
			'contain' => false
		));
		
		$this->assertFalse(empty($data));
		$this->assertInternalType('array',$data);
		$this->assertCount(1,$data);
		
	}
	
	public function testSingleModelLimit() {
		
		$data = $this->_ClogNode->find('all',array(
			'contain' => false,
			'limit' => 2
		));
		
		$this->assertFalse(empty($data));
		$this->assertInternalType('array',$data);
		$this->assertCount(2,$data);		

		$data = $this->_ClogNode->find('all',array(
			'contain' => false,	
			'conditions' => array('ClogNode.node_type_id' => 1),
			'limit' => 2
		));
		
		$this->assertFalse(empty($data));
		$this->assertInternalType('array',$data);
		$this->assertCount(2,$data);
		
	}
	
	public function testContainAll() {
		
		$data = $this->_ClogNode->find('all');
		$containable = $this->_modelContained();
		
		if(!empty($data) && isset($data[0])) {
			
			$containableCheck = array();
			foreach($containable as $contain) {
				
				if(array_key_exists($contain,$data[0])) {
					$containableCheck[] = $contain;
				}
				
			}
			
		}
		
		$this->assertFalse(empty($data));
		$this->assertInternalType('array',$data);	
		$this->assertFalse(empty($containableCheck));	
		
	}
	
	public function testContainNodeType() {
		
		$data = $this->_ClogNode->find('all',array(
			'contain' => array('ClogType')
		));
		
		$this->assertFalse(empty($data));
		$this->assertInternalType('array',$data);
		$this->assertCount(4,$data);
		$this->assertArrayHasKey('ClogType',$data[0]);
		$this->assertFalse(empty($data[0]['ClogType']));
		
	}
	
	public function testContainParent() {
		
		$data = $this->_ClogNode->find('first',array(
			'contain' => array('ClogParentNode'),
			'conditions' => array('ClogNode.id' => '2')
		));
		
		$this->assertFalse(empty($data));
		$this->assertInternalType('array',$data);
		$this->assertCount(2,$data);		
		$this->assertArrayHasKey('ClogParentNode',$data);
		
	}
	
	public function testContainUser() {
		
		$data = $this->_ClogNode->find('all',array(
			'contain' => array('ClogUser')
		));
		
		$this->assertFalse(empty($data));
		$this->assertInternalType('array',$data);
		$this->assertCount(4,$data);
		$this->assertArrayHasKey('ClogUser',$data[0]);
		$this->assertFalse(empty($data[0]['ClogUser']));
	}
	
	public function testContainRels() {
		
		$data = $this->_ClogNode->find('first',array(
			'contain' => array('ClogRelNode','ClogRelObject'),
			'conditions' => array(
				'ClogNode.id' => '1'				
			)
		));
		
		$this->assertFalse(empty($data));
		$this->assertInternalType('array',$data);
		$this->assertCount(3,$data);
		$this->assertArrayHasKey('ClogRelNode',$data);
		$this->assertFalse(empty($data['ClogRelNode']));
		$this->assertTrue(empty($data['ClogRelObject']));
		
		$data = $this->_ClogNode->find('first',array(
			'contain' => array('ClogRelNode','ClogRelObject'),
			'conditions' => array(
				'ClogNode.id' => '4'
			)
		));
		
		$this->assertFalse(empty($data));
		$this->assertInternalType('array',$data);
		$this->assertCount(3,$data);
		$this->assertArrayHasKey('ClogRelObject',$data);
		$this->assertFalse(empty($data['ClogRelObject']));
		$this->assertTrue(empty($data['ClogRelNode']));
	}
	
	public function testWrite() {
		
		$this->_ClogNode->create();
		$this->_ClogNode->save(array(
			'ClogNode' => array(
				'id' => '5',
				'node_type_id' => '1',
				'user_id' => '1',
				'node_parent' => '0',
				'node_created' => date('c'),
				'has_subject' => '0',
				'has_content' => '1',
				'has_media' => '0',
				'has_meta' => '0',
				'node_status' => '0',
				'node_created' => date('c'),				
			)
		));					
		
		$data = $this->_ClogNode->find('first',array(
			'contain' => false,
			'conditions' => array('ClogNode.id' => '5')
		));
		
		$this->assertEqual($this->_ClogNode->id,5);
		$this->assertEqual($data['ClogNode']['has_content'],'1');
		
	}
	
	public function testRemove() {
		
		$this->_ClogNode->create();
		$this->_ClogNode->save(array(
				'ClogNode' => array(
						'id' => '5',
						'node_type_id' => '1',
						'user_id' => '1',
						'node_parent' => '0',
						'node_created' => date('c'),
						'has_subject' => '0',
						'has_content' => '1',
						'has_media' => '0',
						'has_meta' => '0',
						'node_status' => '0',
						'node_created' => date('c'),
				)
		));
		
		$data = $this->_ClogNode->find('first',array(
				'contain' => false,
				'conditions' => array('ClogNode.id' => '5')
		));
		
		$this->assertEqual($this->_ClogNode->id,5);
		$this->assertEqual($data['ClogNode']['has_content'],'1');
		
		$this->_ClogNode->delete($this->_ClogNode->id,false);
		
		$data = $this->_ClogNode->find('all');
		
		$this->assertCount(4,$data);
		
	}
	
	private function _modelContained() {
		return array(
			'ClogType','ClogUser','ClogParentNode','ClogSubject',
			'ClogContent','ClogMedia','ClogPermalink','ClogMeta',
			'ClogParent','ClogRelNode','ClogRelObject'
		);
	}
	
}