<?php

App::uses('CloggyCommon','Lib');

class CloggyCommonTest extends CakeTestCase {
    
    public function testBaseUrl() {
        $base = CloggyCommon::baseUrl();
        $this->assertEqual($base,'/' . Configure::read('Cloggy.url_prefix') . '/');
    }
    
    public function testUrlPath() {
        $url = CloggyCommon::urlPath('test');
        $this->assertEqual($url,  CloggyCommon::baseUrl().'test');
    }
    
    public function testUrlModule() {
        
        $moduleUrl = CloggyCommon::urlModule('test');
        $this->assertEqual($moduleUrl,  CloggyCommon::baseUrl().'module/test');
        
        $moduleUrl = CloggyCommon::urlModule('test', 'test_path');
        $this->assertEqual($moduleUrl,  CloggyCommon::baseUrl().'module/test/test_path');
        
    }
    
}