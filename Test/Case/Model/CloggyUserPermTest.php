<?php

App::uses('CloggyAppModel', 'Cloggy.Model');
App::uses('CloggyUserPerm', 'Cloggy.Model');

class CloggyUserPermTest extends CakeTestCase {

    public $fixtures = array(
        'plugin.cloggy.cloggy_user',
        'plugin.cloggy.cloggy_user_role',
        'plugin.cloggy.cloggy_user_perm'
    );
    private $__CloggyUserPerm;

    public function setUp() {
        parent::setUp();
        $this->__CloggyUserPerm = ClassRegistry::init('CloggyUserPerm');
        $this->__CloggyUserPerm->cacheQueries = false;
    }

    public function testNodeObjects() {
        $this->assertFalse(empty($this->__CloggyUserPerm));
        $this->assertTrue(is_a($this->__CloggyUserPerm, 'CloggyUserPerm'));
    }
    
    public function testRead() {
        
        $data = $this->__CloggyUserPerm->find('first',array(
            'contain' => false
        ));
        
        $this->assertFalse(empty($data));
        $this->assertArrayHasKey('CloggyUserPerm',$data);
        $this->assertArrayHasKey('aco_object',$data['CloggyUserPerm']);
        $this->assertArrayHasKey('aco_adapter',$data['CloggyUserPerm']);
        $this->assertEqual($data['CloggyUserPerm']['aco_object'],'controller/action');        
        $this->assertEqual($data['CloggyUserPerm']['aco_adapter'],'controller');
        
    }        
    
    public function testAccess() {
        
        $checkRoles = $this->__CloggyUserPerm->checkAroPermission('controller/action','controller',1,'roles');
        $this->assertTrue($checkRoles);
        
        $checkUsers = $this->__CloggyUserPerm->checkAroPermission('controller/action','controller',1,'users');
        $this->assertFalse($checkUsers);
        
        $checkUsersDeny = $this->__CloggyUserPerm->checkAroPermission('controller/action','controller',1,'users','deny');        
        $this->assertFalse($checkUsersDeny);
        
        $checkUsersNotRegistered = $this->__CloggyUserPerm->checkAroPermission('controller/action','controller',2,'users');
        $this->assertTrue($checkUsersNotRegistered);
        
        $checkAcoNotRegistered = $this->__CloggyUserPerm->checkAroPermission('controller2/action2','controller',1,'users');
        $this->assertTrue($checkAcoNotRegistered);
        
    }
    
    public function testAccessAll() {
        
        $checkAllowAll = $this->__CloggyUserPerm->isObjectPermAll('controller/action','controller','allow');
        $this->assertTrue($checkAllowAll);
        
        $checkDenyAll = $this->__CloggyUserPerm->isObjectPermAll('controller/action','controller','deny');
        $this->assertFalse($checkDenyAll);
        
        $checkUserDeny = $this->__CloggyUserPerm->checkAroPermission('controller3/action3','controller',1,'users');
        $this->assertTrue($checkUserDeny);
        
    }
    
    public function testCheck() {
        
        $checkAdapter = $this->__CloggyUserPerm->isAdapterExists('controller');
        $this->assertTrue($checkAdapter);
        
        $checkAroObject = $this->__CloggyUserPerm->isRoleObjectExists('roles');
        $this->assertTrue($checkAroObject);
        
        $checkAroObjectId = $this->__CloggyUserPerm->isRoleObjectIdExists(1,'roles');
        $this->assertTrue($checkAroObjectId);
        
    }

    public function testRemoveRole() {

        $this->__CloggyUserPerm->delete(1,false);
        $data = $this->__CloggyUserPerm->find('first',array(
            'contain' => false,
            'conditions' => array('CloggyUserPerm.id' => 1)
        ));

        $this->assertTrue(empty($data));
    }

}