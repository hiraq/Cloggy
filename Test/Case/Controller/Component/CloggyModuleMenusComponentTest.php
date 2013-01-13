<?php

App::uses('Controller', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('ComponentCollection', 'Controller');
App::uses('CloggyModuleMenuComponent', 'Cloggy.Controller/Component');

class CloggyModuleMenusComponentTest extends CakeTestCase {

  private $__CloggyModuleMenu;
  private $__Controller;

  public function setUp() {

    parent::setUp();

    // Setup our component and fake test controller
    $Collection = new ComponentCollection();
    $this->__CloggyModuleMenu = new CloggyModuleMenuComponent($Collection);

    $CakeRequest = new CakeRequest();
    $CakeResponse = new CakeResponse();

    $this->__Controller = new Controller($CakeRequest, $CakeResponse);
    $this->__CloggyModuleMenu->initialize($this->__Controller);
  }

  public function testViewVars() {

    $data = $this->__CloggyModuleMenu->getViewVars();
    $this->assertTrue(empty($data));
  }

  public function testControllername() {

    $this->__Controller->name = 'TestController';
    $name = $this->__CloggyModuleMenu->getRequestedControllerName();

    $this->assertEqual($name, 'TestController');
  }

  public function testEmpty() {

    $data = $this->__CloggyModuleMenu->getMenus();
    $this->assertTrue(empty($data));
  }

  public function testMenus() {

    $this->__Controller->name = 'TestController';
    $this->__CloggyModuleMenu->menus('test', array(
        'test 1' => 'test2'
    ));

    $menus = $this->__CloggyModuleMenu->getMenus();

    $this->assertFalse(empty($menus));
    $this->assertEqual(1, count($menus['TestController']));
    $this->assertArrayHasKey('test', $menus['TestController']);
    $this->assertArrayHasKey('test 1', $menus['TestController']['test']);
  }

  public function testAddMenus() {

    $this->__Controller->name = 'TestController';
    $this->__CloggyModuleMenu->menus('test', array(
        'test 1' => 'test2'
    ));

    $this->__CloggyModuleMenu->add('test2', array(
        'test 2' => 'test3'
    ));

    $menus = $this->__CloggyModuleMenu->getMenus();

    $this->assertFalse(empty($menus));
    $this->assertEqual(2, count($menus['TestController']));

    $this->__Controller->name = 'Test2Controller';
    $this->__CloggyModuleMenu->menus('test', array(
        'test 1' => 'test2'
    ));

    $this->__CloggyModuleMenu->add('test', array(
        'test 2' => 'test3'
    ));

    $menus = $this->__CloggyModuleMenu->getMenus();

    $this->assertFalse(empty($menus));
    $this->assertEqual(2, count($menus));
    $this->assertArrayHasKey('Test2Controller', $menus);
    $this->assertArrayHasKey('test', $menus['Test2Controller']);

    $this->__Controller->name = 'Test3Controller';
    $this->__CloggyModuleMenu->add('test3', array(
        'test 3' => 'test4'
    ));

    $menus = $this->__CloggyModuleMenu->getMenus();
    $this->assertFalse(empty($menus));
    $this->assertEqual(3, count($menus));
  }

  public function testRemove() {

    $this->__Controller->name = 'TestController';
    $this->__CloggyModuleMenu->menus('test', array(
        'test 1' => 'test2'
    ));

    $this->__CloggyModuleMenu->add('test2', array(
        'test 2' => 'test3'
    ));

    $menus = $this->__CloggyModuleMenu->getMenus();

    $this->assertFalse(empty($menus));
    $this->assertEqual(2, count($menus['TestController']));

    $this->__CloggyModuleMenu->remove('test2');

    $menus = $this->__CloggyModuleMenu->getMenus();
    $this->assertEqual(1, count($menus['TestController']));
  }

  public function testSetGroup() {

    $this->__Controller->name = 'TestController';
    $this->__CloggyModuleMenu->setGroup('test', array(
        'test1' => 'test1'
    ));

    $groups = $this->__CloggyModuleMenu->getGroup('test');
    $groupFake = $this->__CloggyModuleMenu->getGroup('testFake');
    $viewVars = $this->__CloggyModuleMenu->getViewVars();

    $this->assertFalse(empty($groups));
    $this->assertInternalType('null', $groupFake);
    $this->assertInternalType('array', $viewVars);
    $this->assertArrayHasKey('cloggy_menus_group', $viewVars);

    $this->__CloggyModuleMenu->setGroup('test', array(
        'test2' => 'test2'
    ));

    $groups = $this->__CloggyModuleMenu->getGroup('test');
    $viewVars = $this->__CloggyModuleMenu->getViewVars();

    $this->assertFalse(empty($groups));
    $this->assertInternalType('array', $groups);
    $this->assertArrayHasKey('test2', $groups);
    $this->assertCount(1, $groups);

    $this->__CloggyModuleMenu->setGroup('test2', array(
        'test3' => 'test3'
    ));

    $groups = $this->__CloggyModuleMenu->getGroup('test2');
    $viewVars = $this->__CloggyModuleMenu->getViewVars();

    $this->assertFalse(empty($groups));
    $this->assertInternalType('array', $groups);
    $this->assertArrayHasKey('test3', $groups);
    $this->assertCount(1, $groups);
    $this->assertArrayHasKey('cloggy_menus_group', $viewVars);
    $this->assertArrayHasKey('test2', $viewVars['cloggy_menus_group']);
    $this->assertInternalType('array', $viewVars['cloggy_menus_group']['test2']);
    $this->assertArrayHasKey('test3', $viewVars['cloggy_menus_group']['test2']);
  }  
  
  public function testParseModuleMenus() {
    
    $isModuleHasConfigMenus = $this->__CloggyModuleMenu->isModuleHasMenus('ModuleTest');
    $isModuleHasConfigMenusFake = $this->__CloggyModuleMenu->isModuleHasMenus('Test');
    
    $this->assertTrue($isModuleHasConfigMenus);
    $this->assertFalse($isModuleHasConfigMenusFake);
    
    $loadFakeConfig = $this->__CloggyModuleMenu->loadConfigModuleMenus('Test','testing');
    $this->assertInternalType('null',$loadFakeConfig);            
    
    $this->__Controller->name = 'Test';
    $this->__Controller->request->params['name'] = 'ModuleTest';
    $this->__CloggyModuleMenu->startup($this->__Controller);
    
    $groups = $this->__CloggyModuleMenu->getGroup('test_sidebar');
    
    $this->assertFalse(empty($groups));
    $this->assertInternalType('array', $groups);
    $this->assertArrayHasKey('Test1', $groups);
    $this->assertArrayHasKey('Test2', $groups);
    $this->assertCount(2, $groups);
    
    $menus = $this->__CloggyModuleMenu->getMenus();
    
    $this->assertFalse(empty($menus[$this->__Controller->name]));
    $this->assertArrayHasKey('module',$menus[$this->__Controller->name]);
    $this->assertEqual(2, count($menus[$this->__Controller->name]['module']));
    
    
  }

}