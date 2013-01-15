<?php

App::uses('Controller', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('ComponentCollection', 'Controller');
App::uses('CloggyModuleMenuComponent', 'Cloggy.Controller/Component');
App::uses('View', 'View');
App::uses('CloggyMenusHelper', 'Cloggy.View/Helper');

class CloggyMenusHelperTest extends CakeTestCase {

    private $__Controller;
    private $__CloggyModuleMenu;

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

    public function testSetupMenus() {

        $this->__Controller->name = 'TestController';
        $this->__CloggyModuleMenu->menus('test', array(
            'test1' => 'test1'
        ));

        $helper = $this->__generateHelperObject();
        $viewVars = $helper->getViewVars();
        $cloggy_menus = $helper->getViewVarValue('cloggy_menus');
        $menus = $helper->menu('test');

        $this->assertFalse(empty($viewVars));
        $this->assertFalse(empty($cloggy_menus));
        $this->assertCount(1, $cloggy_menus);
        $this->assertArrayHasKey('test', $cloggy_menus);
        $this->assertArrayHasKey('test1', $cloggy_menus['test']);
        $this->assertFalse(empty($menus));
    }

    public function testFakeMenus() {

        $helper = $this->__generateHelperObject();
        $menus = $helper->menu('test');

        $this->assertFalse($menus);
    }

    public function testGroupMenus() {

        $this->__Controller->name = 'TestController';
        $this->__CloggyModuleMenu->setGroup('testGroup', array(
            'test1' => 'test1'
        ));

        $helper = $this->__generateHelperObject();
        $viewVars = $helper->getViewVars();
        $menus = $helper->getViewVarValue('cloggy_menus_group');

        $this->assertFalse(empty($viewVars));
        $this->assertTrue(in_array('cloggy_menus_group', $viewVars));
        $this->assertInternalType('array', $menus);
        $this->assertArrayHasKey('testGroup', $menus);
        $this->assertArrayHasKey('test1', $menus['testGroup']);

        $menus = $helper->group('testGroup');
        $menusFake = $helper->group('testGroup2');

        $this->assertInternalType('array', $menus);
        $this->assertArrayHasKey('test1', $menus);
        $this->assertFalse($menusFake);

        $this->__Controller->name = 'TestController';
        $this->__CloggyModuleMenu->setGroup('testGroup2', array(
            'test2' => 'test2'
        ));

        $helper = $this->__generateHelperObject();
        $menus = $helper->groups();

        $this->assertInternalType('array', $menus);
        $this->assertArrayHasKey('testGroup2', $menus);

        unset($this->__Controller->viewVars['cloggy_menus_group']);
        $helper = $this->__generateHelperObject();
        $menus = $helper->groups();

        $this->assertFalse($menus);
    }

    public function testUrl() {

        $helper = $this->__generateHelperObject();
        $url = $helper->getUrl('dashboard');
        $link = $helper->getLink('Test', 'test');

        $this->assertEqual($url, Router::url('dashboard', true));
        $this->assertEqual(htmlentities($link), htmlentities('<a href="' . Router::url('test', true) . '">Test</a>'));
    }

    private function __generateHelperObject() {
        $View = new View($this->__Controller);
        $Helper = new CloggyMenusHelper($View);
        return $Helper;
    }

}