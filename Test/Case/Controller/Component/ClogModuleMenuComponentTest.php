<?php

App::uses('Controller', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('ComponentCollection', 'Controller');
App::uses('ClogModuleMenuComponent', 'Clog.Controller/Component');

class ClogModuleMenuComponentTest extends CakeTestCase {
	
	private $_Controller;
	private $_Collection;
	private $_ClogModuleMenu;
	private $_CakeRequest;
	private $_CakeResponse;

	public function setUp() {
		
		parent::setUp();
		
		$this->_Collection = new ComponentCollection();
		$this->_ClogModuleMenu = new ClogModuleMenuComponent($this->_Collection);
		
		$this->_CakeRequest = new CakeRequest();
        $this->_CakeResponse = new CakeResponse();
        
        $this->_Controller = new Controller($this->_CakeRequest,$this->_CakeResponse);        
        
	}
	
	/**
	 * Test object Controller
	 */
	public function testIsAController() {
		$this->assertIsA($this->_Controller,'Controller');
	}
	
	/**
	 * Test Controller Name
	 */
	public function testComponentControllerName() {
		
		$this->_Controller->name = 'TestController';
		$this->_generateComponentStartup();
		
		$name = $this->_ClogModuleMenu->getRequestedControllerName();
		$this->assertEqual($name, 'TestController');
		
	}
	
	/**
	 * Test Controller count array vars
	 */
	public function testComponentControllerViewVars() {
		
		$this->_Controller->set('tes1','tes2');
		$this->_generateComponentStartup();
		
		$vars = $this->_ClogModuleMenu->getViewVars();
		$this->assertCount(1,$vars);
		
	}
	
	/**
	 * Test Controller view vars
	 */
	public function testComponentControllerTestKeyVar() {
		
		$this->_Controller->set('tes1','tes2');
		$this->_generateComponentStartup();
		
		$vars = $this->_ClogModuleMenu->getViewVars();
		$this->assertArrayHasKey('tes1',$vars);
		$this->expectOutputString($vars['tes1']);
		echo 'tes2';
		
	}
	
	/**
	 * Test Controller vars for clog_menus
	 */
	public function testComponentControllerClogMenusKey() {
		
		$this->_Controller->set('clog_menus',array(
			'clog' => array(
				'dashboard' => '/dashboard',
				'nodes' => '/nodes'
			)
		));
		$this->_generateComponentStartup();
		
		$vars = $this->_ClogModuleMenu->getViewVars();
		$this->assertArrayHasKey('clog_menus',$vars);
		
		$varMenus = $vars['clog_menus'];
		
		/*
		 * test inside clog_menus
		 */
		$this->assertInternalType('array',$varMenus);
		$this->assertArrayHasKey('clog',$varMenus);
		$this->assertCount(2,$varMenus['clog']);		
		
	}
	
	/**
	 * Test Controller vars remove key
	 */
	public function testComponentControllerRemoveKey() {
		
		$this->_Controller->set('clog_menus',array(
				'clog' => array(
						'dashboard' => '/dashboard',
						'nodes' => '/nodes'
				)
		));
		$this->_generateComponentStartup();
		
		unset($this->_Controller->viewVars['clog_menus']);
		$vars = $this->_ClogModuleMenu->getViewVars();
		$this->assertTrue(empty($vars));
		
	}
	
	/**
	 * Test Controller menus var
	 */
	public function testComponentControllerMenusKey() {
		
		$this->_Controller->name = 'Test';
		$this->_generateComponentStartup();
		
		$this->_ClogModuleMenu->menus('tes1', 'tes2');
		$menus = $this->_ClogModuleMenu->getMenus();
		
		$this->assertFalse(empty($menus));
		$this->assertArrayHasKey('Test',$menus);
		
		$this->_ClogModuleMenu->menus('tes1', 'tes2');
		$menus = $this->_ClogModuleMenu->getMenus();
		
		$this->assertCount(1,$menus);
		$this->assertInternalType('array',$menus['Test']);
		$this->assertArrayHasKey('tes1',$menus['Test']);
		
		$this->expectOutputString($menus['Test']['tes1']);
		echo 'tes2';
		
	}
	
	/**
	 * Test Controller merging viewVars clog_menus
	 */
	public function testComponentControllerMenusMerge() {
		
		$this->_Controller->name = 'Test';
		$this->_Controller->set('clog_menus',array(
				'clog' => array(
						'dashboard' => '/dashboard',
						'nodes' => '/nodes'
				)
		));
		$this->_generateComponentStartup();
		
		$this->_ClogModuleMenu->menus('module', array(
			'module_test' => '/module_test',
			'module_test2' => '/module_test2'
		));
		$menus = $this->_ClogModuleMenu->getMenus();
		$vars = $this->_ClogModuleMenu->getViewVars();
		
		$this->assertFalse(empty($menus));
		$this->assertArrayHasKey('Test',$menus);		
		$this->assertArrayHasKey('clog_menus',$vars);
		
		$viewMenus = $vars['clog_menus'];
		
		/*
		 * complete test
		 */
		$this->assertCount(2,$viewMenus);
		$this->assertArrayHasKey('module',$viewMenus);
		$this->assertInternalType('array',$viewMenus['module']);
		$this->assertArrayHasKey('module_test',$viewMenus['module']);
		$this->assertArrayHasKey('module_test2',$viewMenus['module']);
		
		$this->assertEqual($viewMenus['module']['module_test'], '/module_test');
		$this->assertEqual($viewMenus['module']['module_test2'], '/module_test2');
		
	}
	
	/**
	 * Test Controller add menus
	 */
	public function testComponentControllerAddMenus() {
		
		$this->_Controller->name = 'Test';
		$this->_Controller->set('clog_menus',array(
				'clog' => array(
						'dashboard' => '/dashboard',
						'nodes' => '/nodes'
				)
		));
		$this->_generateComponentStartup();
		
		$this->_ClogModuleMenu->menus('module', array(
				'module_test' => '/module_test',
				'module_test2' => '/module_test2'
		));
		
		$this->_ClogModuleMenu->add('module',array(
			'new_module' => '/new_module'
		));
		
		$menus = $this->_ClogModuleMenu->getMenus();
		$vars = $this->_ClogModuleMenu->getViewVars();
		
		$this->assertFalse(empty($menus));
		$this->assertArrayHasKey('Test',$menus);
		$this->assertArrayHasKey('clog_menus',$vars);
		
		$viewMenus = $vars['clog_menus'];
		
		/*
		 * complete test
		*/
		$this->assertCount(2,$viewMenus);
		$this->assertArrayHasKey('module',$viewMenus);		
		$this->assertCount(3,$viewMenus['module']);
		$this->assertArrayHasKey('new_module',$viewMenus['module']);
		$this->assertArrayHasKey('module_test',$viewMenus['module']);
		$this->assertArrayHasKey('module_test2',$viewMenus['module']);
		$this->assertEqual($viewMenus['module']['new_module'], '/new_module');
		
	}
	
	/**
	 * Test Controller add menus if not exists 
	 */
	public function testComponentControllerNotExistsControllerName() {
		
		$this->_Controller->name = 'Test2';
		$this->_Controller->set('clog_menus',array(
				'clog' => array(
						'dashboard' => '/dashboard',
						'nodes' => '/nodes'
				)
		));
		$this->_generateComponentStartup();
		
		$this->_ClogModuleMenu->add('module',array(
				'new_module' => '/new_module'
		));
		
		$menus = $this->_ClogModuleMenu->getMenus();
		$vars = $this->_ClogModuleMenu->getViewVars();
		
		$this->assertFalse(empty($menus));
		$this->assertArrayHasKey('Test2',$menus);
		$this->assertArrayHasKey('clog_menus',$vars);
		
		$this->_ClogModuleMenu->add('module2',array(
				'new_module2' => '/new_module2'
		));
		
		$menus = $this->_ClogModuleMenu->getMenus();
		$vars = $this->_ClogModuleMenu->getViewVars();
		
		/*
		 * complete test
		 */
		$this->assertCount(3,$vars['clog_menus']);
		$this->assertArrayHasKey('module2',$vars['clog_menus']);
		$this->assertArrayHasKey('module',$vars['clog_menus']);
		$this->assertEqual($vars['clog_menus']['module2']['new_module2'],'/new_module2');
		$this->assertEqual($vars['clog_menus']['module']['new_module'],'/new_module');
		
	}
	
	/**
	 * Test Controller remove key menus
	 */
	public function testComponentControllerDeleteKeyMenu() {
		
		$this->_Controller->name = 'Test2';
		$this->_Controller->set('clog_menus',array(
				'clog' => array(
						'dashboard' => '/dashboard',
						'nodes' => '/nodes'
				)
		));
		$this->_generateComponentStartup();
		
		$this->_ClogModuleMenu->remove('clog');
				
		$vars = $this->_ClogModuleMenu->getViewVars();		
		$this->assertTrue(empty($vars['clog_menus']));

		$this->_ClogModuleMenu->add('module',array(
				'new_module' => '/new_module'
		));
		
		$menus = $this->_ClogModuleMenu->getMenus();
		$vars = $this->_ClogModuleMenu->getViewVars();
		
		$this->assertCount(1,$vars['clog_menus']);
		$this->assertArrayHasKey('module',$vars['clog_menus']);
		$this->assertArrayHasKey('Test2',$menus);
		$this->assertArrayHasKey('module',$menus['Test2']);
		$this->assertEqual($vars['clog_menus']['module']['new_module'], '/new_module');		

		$this->_ClogModuleMenu->remove('module');
		
		$menus = $this->_ClogModuleMenu->getMenus();
		$vars = $this->_ClogModuleMenu->getViewVars();
		
		$this->assertTrue(empty($menus['Test2']));
		$this->assertTrue(empty($vars['clog_menus']));
		
	}
	
	/**
	 * Test Controller view vars if clog_menus not exists
	 */
	public function testComponentControllerIfClogMenusNotExists() {
		
		$this->_Controller->name = 'Test';		
		$this->_generateComponentStartup();
		
		$this->_ClogModuleMenu->menus('module', array(
				'module_test' => '/module_test',
				'module_test2' => '/module_test2'
		));
		$menus = $this->_ClogModuleMenu->getMenus();
		$vars = $this->_ClogModuleMenu->getViewVars();
		
		/*
		 * complete test
		 */
		$this->assertArrayHasKey('clog_menus',$vars);
		$this->assertCount(1,$vars['clog_menus']);
		$this->assertArrayHasKey('module',$vars['clog_menus']);
		$this->assertEqual($vars['clog_menus']['module']['module_test'], '/module_test');
		$this->assertEqual($vars['clog_menus']['module']['module_test2'], '/module_test2');
		
	}
	
	/**
	 * Test Controller merge view vars
	 */
	public function testComponentControllerMergeKey() {
		
		$this->_Controller->set('clog_menus',array(
				'clog' => array(
						'dashboard' => '/dashboard',
						'nodes' => '/nodes'
				)
		));
		$this->_generateComponentStartup();
		
		$newValues = array(
			'module' => array(
				'module_test' => '/module_test'
			)
		);
		
		$this->_Controller->viewVars['clog_menus'] = array_merge(
				$this->_Controller->viewVars['clog_menus'],$newValues);
		
		$vars = $this->_ClogModuleMenu->getViewVars();
		$this->assertArrayHasKey('clog_menus',$vars);
		
		$varMenus = $vars['clog_menus'];
		$this->assertCount(2,$varMenus);
		$this->assertArrayHasKey('module',$varMenus);
		
	}
	
	private function _generateComponentStartup() {
		$this->_ClogModuleMenu->startup($this->_Controller);
	}
	
}