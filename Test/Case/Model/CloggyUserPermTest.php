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
        $this->assertEqual($data['CloggyUserPerm']['aco_adapter'],'module');
        
    }        
    
    public function testAccess() {
        
        $checkRoles = $this->__CloggyUserPerm->checkAroPermission('controller/action','module',1,'roles');
        $this->assertTrue($checkRoles);
        
        $checkUsers = $this->__CloggyUserPerm->checkAroPermission('controller/action','module',1,'users');
        $this->assertFalse($checkUsers);
        
        $checkUsersDeny = $this->__CloggyUserPerm->checkAroPermission('controller/action','module',1,'users','deny');
        $this->assertTrue($checkUsersDeny);
        
        $checkUsersNotRegistered = $this->__CloggyUserPerm->checkAroPermission('controller/action','module',2,'users');
        $this->assertFalse($checkUsersNotRegistered);
        
        $checkAcoNotRegistered = $this->__CloggyUserPerm->checkAroPermission('controller2/action2','module',1,'users');
        $this->assertTrue($checkAcoNotRegistered);
        
    }
    
    public function testAccessAll() {
        
        $checkAllowAll = $this->__CloggyUserPerm->isObjectPermAll('controller/action','module','allow');
        $this->assertTrue($checkAllowAll);
        
        $checkDenyAll = $this->__CloggyUserPerm->isObjectPermAll('controller/action','module','deny');
        $this->assertFalse($checkDenyAll);
        
        $checkUserDeny = $this->__CloggyUserPerm->checkAroPermission('controller3/action3','module',1,'users');
        $this->assertTrue($checkUserDeny);
        
    }
    
    public function testCheck() {
        
        $checkAdapter = $this->__CloggyUserPerm->isAdapterExists('module');
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