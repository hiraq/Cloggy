<?php

App::uses('Controller', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('ComponentCollection', 'Controller');
App::uses('CloggyModuleInfoComponent', 'Cloggy.Controller/Component');

/*
 * models
 */
App::uses('CloggyAppModel', 'Cloggy.Model');

class CloggyModuleInfoComponentTest extends CakeTestCase {
    
    public $fixtures = array(
        'plugin.cloggy.cloggy_user',        
        'plugin.cloggy.cloggy_user_role',
        'plugin.cloggy.cloggy_user_perm'
    );

    private $__CloggyModuleInfo;
    private $__Controller;

    public function setUp() {

        parent::setUp();

        // Setup our component and fake test controller
        $Collection = new ComponentCollection();
        $this->__CloggyModuleInfo = new CloggyModuleInfoComponent($Collection);

        $CakeRequest = new CakeRequest();
        $CakeResponse = new CakeResponse();

        $this->__Controller = new Controller($CakeRequest, $CakeResponse);
        $this->__CloggyModuleInfo->startup($this->__Controller);
                
    }

    public function testRegisteredModules() {

        $this->__CloggyModuleInfo->modules();
        $modules = $this->__CloggyModuleInfo->getModules();

        $this->assertFalse(empty($modules));
    }

    public function testModuleExists() {

        $this->__CloggyModuleInfo->modules();
        $check = $this->__CloggyModuleInfo->isModuleExists('ModuleTest');

        $this->assertTrue($check);
    }

    public function testGetModuleInfo() {

        $this->__CloggyModuleInfo->modules();
        $data = $this->__CloggyModuleInfo->getModuleInfo('ModuleTest');

        $this->assertInternalType('array', $data);
        $this->assertFalse(empty($data));
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('desc', $data);
        $this->assertArrayHasKey('author', $data);
        $this->assertArrayHasKey('url', $data);
        $this->assertArrayHasKey('dep', $data);

        $data = $this->__CloggyModuleInfo->getModuleInfo('ModuleTestFake');
        $this->assertInternalType('null', $data);
    }
    
    public function testGetBrokenDeps() {
        
        $this->__CloggyModuleInfo->modules();
        $brokenModules = $this->__CloggyModuleInfo->getModuleBrokenDeps();
        
        $this->assertFalse(empty($brokenModules));
        $this->assertTrue(in_array('ModuleTest',$brokenModules));
        
    }

}