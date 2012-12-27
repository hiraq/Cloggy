<?php

App::uses('Controller', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('ComponentCollection', 'Controller');
App::uses('ClogModuleInfoComponent', 'Clog.Controller/Component');

class ClogModuleInfoComponentTest extends CakeTestCase {
	
	private $_Controller;
	private $_Collection;
	private $_ClogModuleInfo;
	private $_CakeRequest;
	private $_CakeResponse;
	
	public function setUp() {
	
		parent::setUp();
	
		$this->_Collection = new ComponentCollection();
		$this->_ClogModuleInfo = new ClogModuleInfoComponent($this->_Collection);
	
		$this->_CakeRequest = new CakeRequest();
		$this->_CakeResponse = new CakeResponse();
	
		$this->_Controller = new Controller($this->_CakeRequest,$this->_CakeResponse);				
	
	}
	
	public function testModulePath() {
		
		$this->assertTrue(defined('CLOG_PATH_MODULE'));
		$this->assertInternalType('string',CLOG_PATH_MODULE);		
		
	}	
	
	public function testLoadIni() {
		
		$this->_configure();
		$name = Configure::read('basic.name');
		$author = Configure::read('basic.author');
		$desc = Configure::read('basic.desc');
		
		$this->assertTrue(!empty($name));
		$this->assertTrue(!empty($author));
		$this->assertTrue(!empty($desc));
		
		$this->assertEqual($name,'ModuleTest');
		$this->assertEqual($author,'Clog');
		$this->assertEqual($desc,'test description');
		
	}
	
	public function testModuleData() {
		
		$this->_ClogModuleInfo->modules();
		$name = $this->_ClogModuleInfo->getModuleName();
		$desc = $this->_ClogModuleInfo->getModuleDesc();
		$author = $this->_ClogModuleInfo->getModuleAuthor();
		
		$this->assertFalse(empty($name));
		$this->assertFalse(empty($desc));
		$this->assertFalse(empty($author));
		
		$this->assertInternalType('string',$name);
		$this->assertInternalType('string',$desc);
		$this->assertInternalType('string',$author);
		
	}
	
	public function testGetModuleInfo() {
		
		$this->_ClogModuleInfo->modules();
		$moduleInfo = $this->_ClogModuleInfo->getModuleInfo('ModuleTest');
		$moduleInfoFake = $this->_ClogModuleInfo->getModuleInfo('ModuleTest2');
		
		$this->assertInternalType('array',$moduleInfo);			
		$this->assertCount(3,$moduleInfo);
		$this->assertArrayHasKey('name',$moduleInfo);
		$this->assertArrayHasKey('desc',$moduleInfo);
		$this->assertArrayHasKey('author',$moduleInfo);
		$this->assertTrue(is_null($moduleInfoFake));
		
	}
	
	public function testModuleExists() {
		
		$this->_ClogModuleInfo->modules();
		$checkModuleFake = $this->_ClogModuleInfo->isModuleExists('test');
		$checkModuleExists = $this->_ClogModuleInfo->isModuleExists('ModuleTest');
		
		$this->assertFalse($checkModuleFake);
		$this->assertTrue($checkModuleExists);
		
	}

	private function _configure() {
		
		App::uses('IniReader', 'Configure');
		Configure::config('ini',new IniReader(CLOG_PATH_MODULE.'ModuleTest'.DS));
		Configure::load('info.ini','ini');
		
	}
	
}