<?php

App::uses('CloggyAppModel','Cloggy.Model');
App::uses('CloggyUserMeta','Cloggy.Model');

class CloggyUserMetaTest extends CakeTestCase {
	
	public $fixtures = array(
		'plugin.cloggy.cloggy_user',
		'plugin.cloggy.cloggy_user_login',
		'plugin.cloggy.cloggy_user_meta'
	);
	
	private $__CloggyUserMeta;
	
	public function setUp() {
		parent::setUp();
		$this->__CloggyUserMeta = ClassRegistry::init('CloggyUserMeta');
		$this->__CloggyUserMeta->cacheQueries = false;
	}
	
	public function testNodeObjects() {
		$this->assertFalse(empty($this->__CloggyUserMeta));
		$this->assertTrue(is_a($this->__CloggyUserMeta,'CloggyUserMeta'));
	}
	
	public function testGetUserMeta() {
		
		$data = $this->__CloggyUserMeta->getUserMeta(1);
		
		$this->assertFalse(empty($data));		
		$this->assertCount(1,$data);
		$this->assertArrayHasKey('CloggyUserMeta',$data[0]);
		$this->assertEqual($data[0]['CloggyUserMeta']['meta_key'],'test user key');
		$this->assertEqual($data[0]['CloggyUserMeta']['meta_value'],'test user value');
		
	}
	
	public function testSaveMeta() {
		
		$metaIds = $this->__CloggyUserMeta->saveMeta(1,array(
			'test_key_1' => 'test key 1',
			'test_key_2' => 'test key 2'
		));
		
		$this->assertInternalType('array',$metaIds);	

		$data = $this->__CloggyUserMeta->getUserMeta(1);
		
		$this->assertFalse(empty($data));
		$this->assertCount(3,$data);
		$this->assertArrayHasKey('CloggyUserMeta',$data[1]);
		$this->assertEqual($data[1]['CloggyUserMeta']['meta_key'],'test_key_1');
		$this->assertEqual($data[1]['CloggyUserMeta']['meta_value'],'test key 1');
		$this->assertEqual($data[2]['CloggyUserMeta']['meta_key'],'test_key_2');
		$this->assertEqual($data[2]['CloggyUserMeta']['meta_value'],'test key 2');
		
		$checkMetaExists1 = $this->__CloggyUserMeta->isMetaExists(1,'test_key_1');
		$checkMetaExists2 = $this->__CloggyUserMeta->isMetaExists(1,'test_key_2');
		
		$this->assertTrue($checkMetaExists1);
		$this->assertTrue($checkMetaExists2);
		
		$metaIdsFake = $this->__CloggyUserMeta->saveMeta(1,'test');
		$this->assertFalse($metaIdsFake);
		
	}	

	public function testUpdateMeta() {
		
		$updateMeta = $this->__CloggyUserMeta->updateMeta(1,'test user key','testing');		
		$this->assertTrue($updateMeta);
		
		$data = $this->__CloggyUserMeta->getUserMeta(1);
		
		$this->assertFalse(empty($data));
		$this->assertCount(1,$data);
		$this->assertArrayHasKey('CloggyUserMeta',$data[0]);
		$this->assertEqual($data[0]['CloggyUserMeta']['meta_key'],'test user key');
		$this->assertEqual($data[0]['CloggyUserMeta']['meta_value'],'testing');
		
		$updateMetaFake = $this->__CloggyUserMeta->updateMeta(2,'test user key','testing');
		$this->assertFalse($updateMetaFake);
		
	}
	
	public function testRemoveMeta() {
		
		$this->__CloggyUserMeta->delete(1,false);
		$data = $this->__CloggyUserMeta->getUserMeta(1);
		
		$this->assertTrue(empty($data));
		
	}
	
}