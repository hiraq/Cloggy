<?php

App::uses('Controller', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('ComponentCollection', 'Controller');
App::uses('CloggyModuleInfoComponent', 'Cloggy.Controller/Component');

class CloggyModuleInfoComponentTest extends CakeTestCase {
	
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
		
		$this->assertInternalType('array',$data);
		$this->assertFalse(empty($data));
		$this->assertArrayHasKey('name',$data);
		
		$data = $this->__CloggyModuleInfo->getModuleInfo('ModuleTestFake');
		$this->assertInternalType('null',$data);
		
	}
	
}