<?php

App::uses('Controller', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('ComponentCollection', 'Controller');
App::uses('CloggyModuleMenuComponent', 'Cloggy.Controller/Component');

class CloggyModuleMenusComponentTest extends CakeTestCase {
	
	private $_CloggyModuleMenu;
	private $_Controller;
	
	public function setUp() {
		
		parent::setUp();
		
		// Setup our component and fake test controller
		$Collection = new ComponentCollection();
		$this->_CloggyModuleMenu = new CloggyModuleMenuComponent($Collection);
		
		$CakeRequest = new CakeRequest();
		$CakeResponse = new CakeResponse();
		
		$this->_Controller = new Controller($CakeRequest, $CakeResponse);
		$this->_CloggyModuleMenu->startup($this->_Controller);
		
	}
	
	public function testViewVars() {
		
		$data = $this->_CloggyModuleMenu->getViewVars();
		$this->assertTrue(empty($data));
		
	}
	
	public function testControllername() {
		
		$this->_Controller->name = 'TestController';
		$name = $this->_CloggyModuleMenu->getRequestedControllerName();
		
		$this->assertEqual($name,'TestController');
		
	}
	
	public function testEmpty() {
				
		$data = $this->_CloggyModuleMenu->getMenus();		
		$this->assertTrue(empty($data));
		
	}
	
	public function testMenus() {
		
		$this->_Controller->name = 'TestController';
		$this->_CloggyModuleMenu->menus('test', array(
			'test 1' => 'test2'
		));
		
		$menus = $this->_CloggyModuleMenu->getMenus();
		
		$this->assertFalse(empty($menus));
		$this->assertEqual(1,count($menus['TestController']));
		$this->assertArrayHasKey('test',$menus['TestController']);		
		$this->assertArrayHasKey('test 1',$menus['TestController']['test']);
		
	}
	
	public function testAddMenus() {
		
		$this->_Controller->name = 'TestController';
		$this->_CloggyModuleMenu->menus('test', array(
			'test 1' => 'test2'
		));
		
		$this->_CloggyModuleMenu->add('test2',array(
			'test 2' => 'test3'
		));
		
		$menus = $this->_CloggyModuleMenu->getMenus();
		
		$this->assertFalse(empty($menus));
		$this->assertEqual(2,count($menus['TestController']));
		
		$this->_Controller->name = 'Test2Controller';
		$this->_CloggyModuleMenu->menus('test', array(
			'test 1' => 'test2'
		));
		
		$this->_CloggyModuleMenu->add('test',array(
			'test 2' => 'test3'
		));
		
		$menus = $this->_CloggyModuleMenu->getMenus();
		
		$this->assertFalse(empty($menus));
		$this->assertEqual(2,count($menus));
		$this->assertArrayHasKey('Test2Controller',$menus);
		$this->assertArrayHasKey('test',$menus['Test2Controller']);		
		
		$this->_Controller->name = 'Test3Controller';
		$this->_CloggyModuleMenu->add('test3',array(
			'test 3' => 'test4'
		));
		
		$menus = $this->_CloggyModuleMenu->getMenus();
		$this->assertFalse(empty($menus));
		$this->assertEqual(3,count($menus));
		
	}
	
	public function testRemove() {
		
		$this->_Controller->name = 'TestController';
		$this->_CloggyModuleMenu->menus('test', array(
			'test 1' => 'test2'
		));
		
		$this->_CloggyModuleMenu->add('test2',array(
			'test 2' => 'test3'
		));
		
		$menus = $this->_CloggyModuleMenu->getMenus();
		
		$this->assertFalse(empty($menus));
		$this->assertEqual(2,count($menus['TestController']));
		
		$this->_CloggyModuleMenu->remove('test2');
		
		$menus = $this->_CloggyModuleMenu->getMenus();		
		$this->assertEqual(1,count($menus['TestController']));
		
	}
	
	public function testSetGroup() {
		
		$this->_Controller->name = 'TestController';
		$this->_CloggyModuleMenu->setGroup('test', array(
			'test1' => 'test1'
		));
		
		$groups = $this->_CloggyModuleMenu->getGroup('test');
		$groupFake = $this->_CloggyModuleMenu->getGroup('testFake');
				
		$this->assertFalse(empty($groups));
		$this->assertInternalType('null',$groupFake);
		
	}
	
}