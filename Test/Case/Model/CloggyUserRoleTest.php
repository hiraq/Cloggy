<?php

App::uses('CloggyAppModel', 'Cloggy.Model');
App::uses('CloggyUserRole', 'Cloggy.Model');

class CloggyUserRoleTest extends CakeTestCase {

    public $fixtures = array(
        'plugin.cloggy.cloggy_user',
        'plugin.cloggy.cloggy_user_role'        
    );
    private $__CloggyUserRole;

    public function setUp() {
        parent::setUp();
        $this->__CloggyUserRole = ClassRegistry::init('CloggyUserRole');
        $this->__CloggyUserRole->cacheQueries = false;
    }

    public function testNodeObjects() {
        $this->assertFalse(empty($this->__CloggyUserRole));
        $this->assertTrue(is_a($this->__CloggyUserRole, 'CloggyUserRole'));
    }
    
    public function testRead() {
        
        $data = $this->__CloggyUserRole->find('first',array(
            'contain' => false
        ));
        
        $this->assertFalse(empty($data));
        $this->assertArrayHasKey('CloggyUserRole',$data);
        $this->assertArrayHasKey('role_name',$data['CloggyUserRole']);
        $this->assertEqual($data['CloggyUserRole']['role_name'],'admin');        
        
    }
    
    public function testWrite() {
        
        /*
         * test not exits
         * should return id
         */
        $roleId = $this->__CloggyUserRole->createRole('test2');        
        $this->assertEqual($roleId,3);
        
        /*
         * test if exists
         * should return false
         */
        $roleIdFake = $this->__CloggyUserRole->createRole('test');
        $this->assertFalse($roleIdFake);
        
        /*
         * test check data
         */
        $data = $this->__CloggyUserRole->find('first',array(
            'contain' => false,
            'conditions' => array('CloggyUserRole.id' => $roleId)
        ));
        
        $this->assertFalse(empty($data));
        $this->assertArrayHasKey('CloggyUserRole',$data);
        $this->assertArrayHasKey('role_name',$data['CloggyUserRole']);
        $this->assertEqual($data['CloggyUserRole']['role_name'],'test2');    
        
    }
    
    public function testUpdate() {
        
        $this->__CloggyUserRole->updateRole(1,'test');
        
        /*
         * test check data
         */
        $data = $this->__CloggyUserRole->find('first',array(
            'contain' => false,
            'conditions' => array('CloggyUserRole.id' => 1)
        ));
        
        $this->assertFalse(empty($data));
        $this->assertArrayHasKey('CloggyUserRole',$data);
        $this->assertArrayHasKey('role_name',$data['CloggyUserRole']);
        $this->assertEqual($data['CloggyUserRole']['role_name'],'test'); 
        
    }
    
    public function testUserContainable() {
        
        $data = $this->__CloggyUserRole->find('first',array(
            'contain' => array('CloggyUser'),
            'conditions' => array('CloggyUserRole.id' => 1)
        ));
        
        $this->assertFalse(empty($data));
        $this->assertArrayHasKey('CloggyUserRole',$data);
        $this->assertArrayHasKey('CloggyUser',$data);
        $this->assertArrayHasKey('role_name',$data['CloggyUserRole']);
        $this->assertEqual($data['CloggyUserRole']['role_name'],'admin'); 
        $this->assertEqual($data['CloggyUser'][0]['user_name'],'test user');
        
    }

    public function testRemoveRole() {

        $this->__CloggyUserRole->delete(1,false);
        $data = $this->__CloggyUserRole->find('first',array(
            'contain' => false
        ));

        $this->assertCount(1,$data);
    }

}