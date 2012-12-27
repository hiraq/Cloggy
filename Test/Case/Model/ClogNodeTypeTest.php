<?php

App::uses('ClogAppModel','Clog.Model');
App::uses('ClogNodeType', 'Clog.Model');

class ClogNodeTypeTest extends CakeTestCase {
	
	public $fixtures = array(
		'plugin.clog.clog_node_type',
		'plugin.clog.clog_user',
		'plugin.clog.clog_node'
	);
	
	private $_ClogNodeType;
	
	public function setUp() {
		parent::setUp();
		$this->_ClogNodeType = ClassRegistry::init('ClogNodeType');
		$this->_ClogNodeType->cacheQueries = false;
	}
	
	public function testFindAllSingleModel() {
		
		/*
		 * unbind all relations
		 */
		$this->_ClogNodeType->unbindModel(
			array(
				'hasMany' => array('ClogNode'),
				'belongsTo' => array('ClogUser')
			)
		);
		
		$data = $this->_ClogNodeType->find('all');
		
		$this->assertInternalType('array',$data);
		$this->assertFalse(empty($data));
		$this->assertCount(2,$data);	
		
	}
	
	public function testFindFirstSingleModel() {
		
		/*
		 * unbind all relations
		*/
		$this->_ClogNodeType->unbindModel(
			array(
				'hasMany' => array('ClogNode'),
				'belongsTo' => array('ClogUser')
			)
		);
		
		$data = $this->_ClogNodeType->find('first');
		
		$this->assertInternalType('array',$data);
		$this->assertFalse(empty($data));
		$this->assertCount(1,$data);
		$this->assertArrayHasKey('ClogNodeType',$data);
		$this->assertArrayHasKey('id',$data['ClogNodeType']);
		$this->assertEqual($data['ClogNodeType']['id'],'1');
		
	}
	
	public function testFindAll() {				
		
		$data = $this->_ClogNodeType->find('all');
		
		$this->assertInternalType('array',$data);
		$this->assertFalse(empty($data));		
		$this->assertCount(2,$data);
		
	}
	
	public function testFindAllConditions() {
		
		/*
		 * test field : id
		 */
		$data = $this->_ClogNodeType->find('all',array(
			'contain' => false,
			'conditions' => array('ClogNodeType.id' => 1)
		));
		
		$this->assertInternalType('array',$data);
		$this->assertFalse(empty($data));
		$this->assertCount(1,$data);
		
		/*
		 * test field: id
		 */
		$data = $this->_ClogNodeType->find('all',array(
				'contain' => false,
				'conditions' => array('ClogNodeType.id' => 2)
		));
		
		$this->assertInternalType('array',$data);
		$this->assertFalse(empty($data));
		$this->assertCount(1,$data);
		
		/*
		 * test field: node_type_name
		 */
		$data = $this->_ClogNodeType->find('all',array(
				'contain' => false,
				'conditions' => array('ClogNodeType.node_type_name' => 'node type name')
		));
		
		$this->assertInternalType('array',$data);
		$this->assertFalse(empty($data));
		$this->assertCount(1,$data);
		
		/*
		 * test field: node_type_name
		 * if not exists
		 */
		$data = $this->_ClogNodeType->find('all',array(
				'contain' => false,
				'conditions' => array('ClogNodeType.node_type_name' => 'test')
		));
		
		$this->assertInternalType('array',$data);
		$this->assertTrue(empty($data));
		$this->assertCount(0,$data);
	}
	
	public function testFindAllLimit() {
		
		/*
		 * test field : id
		*/
		$data = $this->_ClogNodeType->find('all',array(
			'contain' => false,
			'conditions' => array('ClogNodeType.id' => 1),
			'limit' => 1
		));
		
		$this->assertInternalType('array',$data);
		$this->assertFalse(empty($data));
		$this->assertCount(1,$data);
		
	}
	
	public function testFindAllContainable() {
		
		/*
		 * test field : id
		*/
		$data = $this->_ClogNodeType->find('all',array(
			'contain' => array('ClogNode','ClogUser')			
		));
		
		$this->assertInternalType('array',$data);
		$this->assertFalse(empty($data));
		$this->assertCount(2,$data);
		$this->assertArrayHasKey('ClogNode',$data[0]);
		$this->assertArrayHasKey('ClogUser',$data[0]);
		$this->assertArrayHasKey('user_name',$data[0]['ClogUser']);				
		
	}
	
	public function testFindAllContainableConditions() {
		
		/*
		 * test field : id
		*/
		$data = $this->_ClogNodeType->find('all',array(
			'contain' => array('ClogNode'),
			'conditions' => array('ClogNodeType.id' => 1)			
		));
		
		$this->assertInternalType('array',$data);
		$this->assertFalse(empty($data));
		$this->assertCount(1,$data);
		$this->assertArrayHasKey('ClogNode',$data[0]);
		$this->assertCount(3,$data[0]['ClogNode']);
		
	}
	
	public function testFindAllContainableLimit() {
		
		/*
		 * test field : id
		*/
		$data = $this->_ClogNodeType->find('all',array(
			'contain' => array('ClogNode'),
			'conditions' => array('ClogNodeType.id' => 1),
			'limit' => 1
		));
		
		$this->assertInternalType('array',$data);
		$this->assertFalse(empty($data));
		$this->assertCount(1,$data);
		$this->assertArrayHasKey('ClogNode',$data[0]);
		$this->assertCount(3,$data[0]['ClogNode']);
		
	}
	
	public function testFindFirst() {
		
		/*
		 * test field : id
		*/
		$data = $this->_ClogNodeType->find('first',array(
			'contain' => false,
			'conditions' => array('ClogNodeType.id' => 1),
			'limit' => 1
		));
		
		$this->assertInternalType('array',$data);
		$this->assertFalse(empty($data));
		$this->assertCount(1,$data);
		$this->assertArrayHasKey('ClogNodeType',$data);	

		/*
		 * test field : id
		*/
		$data = $this->_ClogNodeType->find('first',array(
			'contain' => array('ClogNode','ClogUser'),
			'conditions' => array('ClogNodeType.id' => 1),
			'limit' => 1
		));
		
		$this->assertInternalType('array',$data);
		$this->assertFalse(empty($data));
		$this->assertCount(3,$data);
		$this->assertArrayHasKey('ClogNodeType',$data);
		$this->assertArrayHasKey('ClogNode',$data);
		$this->assertArrayHasKey('ClogUser',$data);
		
	}		
	
	public function testWrite() {
		
		/*
		 * write to database
		 */
		$this->_ClogNodeType->create();
		$this->_ClogNodeType->save(array(
			'ClogNodeType' => array(
				'user_id' => '1',
				'node_type_name' => 'test write',
				'node_type_desc' => 'test write',
				'node_type_created' => date('c')
			)
		));
		
		/*
		 * test field : id
		*/
		$data = $this->_ClogNodeType->find('first',array(
			'contain' => array('ClogNode','ClogUser'),
			'conditions' => array('ClogNodeType.node_type_name' => 'test write'),
			'limit' => 1
		));
		
		$this->assertFalse(empty($this->_ClogNodeType->id));		
		$this->assertFalse(empty($data));
		$this->assertArrayHasKey('ClogUser',$data);
		$this->assertArrayHasKey('node_type_name',$data['ClogNodeType']);
		$this->assertEqual($data['ClogNodeType']['node_type_name'],'test write');
		$this->assertEqual($data['ClogNodeType']['node_type_desc'],'test write');
		
	}
	
	public function testRemove() {
		
		/*
		 * write to database
		*/
		$this->_ClogNodeType->create();
		$this->_ClogNodeType->save(array(
				'ClogNodeType' => array(
						'user_id' => '1',
						'node_type_name' => 'test write',
						'node_type_desc' => 'test write',
						'node_type_created' => date('c')
				)
		));
		
		/*
		 * test field : id
		*/
		$data = $this->_ClogNodeType->find('first',array(
				'contain' => array('ClogNode','ClogUser'),
				'conditions' => array('ClogNodeType.node_type_name' => 'test write'),				
		));
		
		$this->assertFalse(empty($data));
		
	}
	
}