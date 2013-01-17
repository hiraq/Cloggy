<?php

App::uses('CakeRoute','Routing/Route');
App::uses('CloggyModuleRoute', 'CustomRouter');

class CloggyModuleRouteTest extends CakeTestCase {
    
    private $__CloggyRoute;
    private $__base;
    private $__route;
    
    public function setUp() {
        parent::setUp();        
        $this->__base = '/' . Configure::read('Cloggy.url_prefix');
        $this->__CloggyRoute = new CloggyModuleRoute($this->__base.'/module/:name/');
        $this->__route = $this->__CloggyRoute->compile();
    }
    
    public function testRoute() {                
        
        $this->assertEqual($this->__CloggyRoute->template,$this->__base.'/module/:name/');
        $this->assertRegExp($this->__route, '/cloggy/module/docs/');    
        
        $result = $this->__CloggyRoute->parse('/cloggy/module/docs');
        $this->assertFalse($result);
        
        $result = $this->__CloggyRoute->parse('/cloggy/module/cloggy_docs/');
        $this->assertInternalType('array',$result);
        $this->assertArrayHasKey('name',$result);
        $this->assertEqual($result['name'],'cloggy_docs');
        
        $result = $this->__CloggyRoute->parse('/cloggy/module/docs/');
        $this->assertFalse($result);
        
        $result = $this->__CloggyRoute->parse('/cloggy/module/docs/test');
        $this->assertFalse($result);
        
        Configure::write('Cloggy.modules',array());
        $result = $this->__CloggyRoute->parse('/cloggy/module/docs');
        $this->assertFalse($result);        
        
    }       
    
}