<?php

App::uses('Controller', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('View', 'View');
App::uses('CloggyAssetHelper', 'Cloggy.View/Helper');

class CloggyAssetHelperTest extends CakeTestCase {

    private $__CloggyAsset;
    private $__base;

    public function setUp() {

        parent::setUp();

        $CakeRequest = new CakeRequest();
        $CakeResponse = new CakeResponse();
        
        $Controller = new Controller($CakeRequest, $CakeResponse);
        $View = new View($Controller);

        $this->__CloggyAsset = new CloggyAssetHelper($View);
        $this->__base = Router::url('/', true) . Configure::read('Cloggy.url_prefix') . '/' . Configure::read('Cloggy.theme_used');                
                
    }

    public function testVendor() {
        
        $vendorUrl = $this->__CloggyAsset->getVendorUrl('test');
        $this->assertEqual($vendorUrl, $this->__base . '/vendor/test');

        $vendorCssLink = $this->__CloggyAsset->getVendorHtmlTag('test', 'css');        
        $this->assertEqual(htmlentities($this->__replaceConsole($vendorCssLink)), htmlentities('<link rel="stylesheet" type="text/css" href="/cloggy/default/vendor/test.css" />'));

        $vendorJsLink = $this->__CloggyAsset->getVendorHtmlTag('test', 'js');
        $this->assertEqual(htmlentities($this->__replaceConsole($vendorJsLink)), htmlentities('<script type="text/javascript" src="/cloggy/default/vendor/test.js"></script>'));

        $vendorUnknownType = $this->__CloggyAsset->getVendorHtmlTag('test', 'tst');
        $this->assertFalse($vendorUnknownType);
    }

    public function testJs() {

        $jsUrl = $this->__CloggyAsset->getJsUrl('test');
        $this->assertEqual($jsUrl, $this->__base . '/app/js/test.js');

        $jsTag = $this->__CloggyAsset->getJsHtmlTag('test');
        $this->assertEqual($this->__replaceConsole($jsTag), '<script type="text/javascript" src="/cloggy/default/app/js/test.js"></script>');
    }

    public function testCss() {

        $cssUrl = $this->__CloggyAsset->getCssUrl('test');
        $this->assertEqual($cssUrl, $this->__base . '/app/css/test.css');

        $cssTag = $this->__CloggyAsset->getCssHtmlTag('test');
        $this->assertEqual(htmlentities($this->__replaceConsole($cssTag)), htmlentities('<link rel="stylesheet" type="text/css" href="/cloggy/default/app/css/test.css" />'));
    }
    
    private function __replaceConsole($link) {
        
        $consolePath = APP.'Console';
        if (strstr($link,$consolePath)) {
            return str_replace($consolePath,'',$link);
        }
        
        return $link;
        
    }

}