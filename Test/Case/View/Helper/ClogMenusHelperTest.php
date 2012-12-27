<?php

App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('ClogMenusHelper', 'Clog.View/Helper');
App::uses('HtmlHelper', 'View/Helper');

class ClogMenusHelperTest extends CakeTestCase {
	
	public $Controller;
	public $ClogMenus;
	public $Html;
	public $BaseUrl;	
	public $Vars;
	
	public function setUp() {
	
		parent::setUp();
		$this->Controller = new Controller();
		$this->Controller->set('tes1','tes1');		
		$this->Controller->set('clog_menus',array(
			'clog' => array(
				'dashboard' => '/dashboard',
				'nodes' => '/nodes',
				'users' => '/users'
			),
			'clog_test' => array()
		));		
		
		$View = new View($this->Controller);
	
		/*
		 * helpers for test
		*/
		$this->ClogMenus = new ClogMenusHelper($View);
		$this->Html = new HtmlHelper($View);
	
		/*
		 * base url + theme configuration
		*/
		$this->BaseUrl = Router::url('/',true);				
		$this->Vars = $this->ClogMenus->getViewVars();
	
	}
	
	/**
	 * Test object ClogAsset
	 */
	public function testIsAClogMenus() {
		$this->assertIsA($this->ClogMenus, 'ClogMenusHelper');
	}
	
	/**
	 * Test object Html
	 */
	public function testIsAHtml() {
		$this->assertIsA($this->Html, 'HtmlHelper');
	}
	
	/**
	 * Test base url
	 */
	public function testBaseUrl() {
	
		$url = $this->Html->url(array(
				'controller' => 'test_controller',
				'action' => 'index',
				'plugin' => 'clog'
		),true);
	
		$this->assertContains($this->BaseUrl,$url);
	
	}
	
	/**
	 * Test type vars
	 */
	public function testVarsIsArray() {		
		$this->assertInternalType('array',$this->Vars);
	}

	/**
	 * Test key var exists
	 */
	public function testKeyVarsIsExists() {
		$checkInArray = in_array('tes1',$this->Vars);
		$this->assertTrue($checkInArray);
	}
	
	/**
	 * Test value var
	 */
	public function testValueVar() {
		$this->assertEqual($this->ClogMenus->getViewVarValue('tes1'),'tes1');
	}
		
	/**
	 * Test menus values type
	 */
	public function testMenusArray() {
		$values = $this->ClogMenus->getViewVarValue('clog_menus');
		$this->assertInternalType('array',$values);
	}		
	
	/**
	 * Test link html tag
	 */
	public function testLink() {
		$link = $this->ClogMenus->getLink('tes', '/tes');
		$link = htmlentities($link);

		$linkExpected = '<a href="'.Router::url('/tes',true).'">Tes</a>';
		$linkExpected = htmlentities($linkExpected);
		
		$this->assertEqual($link, $linkExpected);
	}
	
	/**
	 * Test url
	 */
	public function testUrl() {
		$link = $this->ClogMenus->getUrl('/tes');
		$linkExpected = Router::url('/tes',true);
		$this->assertEqual($link,$linkExpected);
	}
		
	/**
	 * Test menu based on key
	 * if key exists
	 */
	public function testMenuCorrectKey() {
		$menus = $this->ClogMenus->menu('clog');
		$this->assertInternalType('array',$menus);
	}
	
	/**
	 * Test menu based on key
	 * if key menu empty
	 */
	public function testMenuEmptyValue() {
		$menus = $this->ClogMenus->menu('clog_test');
		$this->assertFalse($menus);
	}
	
	/**
	 * Test menu based on key
	 * if key not exists
	 */
	public function testMenuNotExistsKey() {
		$menus = $this->ClogMenus->menu('test');
		$this->assertFalse($menus);
	}
	
	/**
	 * Test group menu if not exists
	 */
	public function testMenuNotExistsGroup() {
		$menus = $this->ClogMenus->menu('test','just_test');
		$this->assertFalse($menus);
	}
}