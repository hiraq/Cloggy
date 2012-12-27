<?php

App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('ClogAssetHelper', 'Clog.View/Helper');
App::uses('HtmlHelper', 'View/Helper');

class ClogAssetHelperTest extends CakeTestCase {
	
	public $ClogAsset;
	public $Html;
	public $BaseUrl;
	public $Theme;
	
	public function setUp() {
		
		parent::setUp();
		$Controller = new Controller();
		$View = new View($Controller);
		
		/*
		 * helpers for test
		 */
		$this->ClogAsset = new ClogAssetHelper($View);
		$this->Html = new HtmlHelper($View);
		
		/*
		 * base url + theme configuration
		 */
		$this->BaseUrl = Router::url('/',true);		
		$this->Theme = Configure::read('Clog.theme_used');				
		
	}
	
	/**
	 * Test object ClogAsset
	 */
	public function testIsAClogAsset() {
		$this->assertIsA($this->ClogAsset, 'ClogAssetHelper');
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
	 * Test js url
	 */
	public function testGetJsUrl() {				
				
		$url = $this->ClogAsset->getJsUrl('clog.global');
		$urlMatch = $this->BaseUrl.'clog/'.$this->Theme.'/app/js/clog.global.js';
		
		$this->assertEqual($url, $urlMatch);
		
	}
	
	/**
	 * Test js html tag
	 */
	public function testGetJsHtmlTag() {
		
		$url = $this->ClogAsset->getJsHtmlTag('clog.global');
		$url = htmlentities($url);
		
		$urlMatch = '<script type="text/javascript" src="/clog/'.$this->Theme.'/app/js/clog.global.js"></script>';
		$urlMatch = htmlentities($urlMatch);
		
		$this->assertEqual($url, $urlMatch);
		
	}
	
	/**
	 * Test css url
	 */
	public function testGetCssUrl() {
		
		$url = $this->ClogAsset->getCssUrl('style');
		$urlMatch = $this->BaseUrl.'clog/'.$this->Theme.'/app/css/style.css';
		
		$this->assertEqual($url, $urlMatch);
		
	}
	
	/**
	 * Test css html tag
	 */
	public function testGetCssHtmlTag() {
		
		$url = $this->ClogAsset->getCssHtmlTag('style');
		$url = htmlentities($url);
		
		$urlMatch = '<link rel="stylesheet" type="text/css" href="/clog/'.$this->Theme.'/app/css/style.css" />';		
		$urlMatch = htmlentities($urlMatch);
		
		$this->assertEqual($url, $urlMatch);
		
	}
	
	/**
	 * Test vendor url
	 */
	public function testGetVendorUrl() {				
		
		$url = $this->ClogAsset->getVendorUrl('json2.js');
		$urlMatch = $this->BaseUrl.'clog/'.$this->Theme.'/vendors/json2.js';
		
		$this->assertEqual($url,$urlMatch);
		
	}	

	/**
	 * Test vendor js html tag
	 */
	public function testGetVendorJsHtmlTag() {
		
		$url = $this->ClogAsset->getVendorHtmlTag('json2','js');
		$url = htmlentities($url);
		
		$urlMatch = '<script type="text/javascript" src="/clog/'.$this->Theme.'/vendors/json2.js"></script>';
		$urlMatch = htmlentities($urlMatch);
		
		$this->assertEqual($url, $urlMatch);
		
	}
	
	/**
	 * Test vendor css html tag
	 */
	public function testGetVendorCssHtmlTag() {
	
		$url = $this->ClogAsset->getVendorHtmlTag('bootstrap/css/bootstrap.min','css');
		$url = htmlentities($url);
	
		$urlMatch = '<link rel="stylesheet" type="text/css" href="/clog/'.$this->Theme.'/vendors/bootstrap/css/bootstrap.min.css" />';
		$urlMatch = htmlentities($urlMatch);
	
		$this->assertEqual($url, $urlMatch);
	
	}
	
	/**
	 * Test vendor unknown tag
	 */
	public function testGetVendorUnknownTag() {
		
		$url = $this->ClogAsset->getVendorHtmlTag('json2','css3');
		$this->assertEqual($url,false);
		
	}
	
}