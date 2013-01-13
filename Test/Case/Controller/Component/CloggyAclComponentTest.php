<?php

App::uses('Controller', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('ComponentCollection', 'Controller');
App::uses('CloggyAclComponent', 'Cloggy.Controller/Component');

class TestFakeController extends Controller {

  public $cloggyModuleAccess = array(
      'publisher' => array('add', 'edit'),
      'editor' => array('add', 'edit', 'delete')
  );
  public $components = array('Cloggy.CloggyAcl');
  public $autoRender = false;

  public function beforeFilter() {
    parent::beforeFilter();
    $this->request->params['action'] = 'add';
    $this->CloggyAcl->setUserRole('test_fake');
    $this->CloggyAcl->setCallbackIfFailed('testFailed');
  }

  public function testFailed() {
    echo 'failed!';
  }

}

class CloggyAclComponentTest extends CakeTestCase {

  private $__CloggyAcl;
  private $__Controller;

  public function setUp() {

    parent::setUp();

    // Setup our component and fake test controller
    $Collection = new ComponentCollection();
    $CakeRequest = new CakeRequest();
    $CakeResponse = new CakeResponse();

    $this->__CloggyAcl = new CloggyAclComponent($Collection);
    $this->__Controller = new Controller($CakeRequest, $CakeResponse);
  }

  public function testObjects() {
    $this->__startupAcl();
    $this->assertFalse(empty($this->__Controller));
    $this->assertFalse(empty($this->__CloggyAcl));
  }

  public function testGenerateRules() {

    $this->__Controller->cloggyModuleAccess = array(
        'publisher' => array('add', 'edit'),
        'editor' => array('add', 'edit', 'delete')
    );

    $this->__startupAcl();

    $accessRules = $this->__Controller->cloggyModuleAccess;

    $this->assertInternalType('array', $accessRules);
    $this->assertcount(2, $accessRules);
  }

  public function testAccess() {

    $this->__Controller->cloggyModuleAccess = array(
        'publisher' => array('add', 'edit'),
        'editor' => array('add', 'edit', 'delete')
    );

    $this->__Controller->params['action'] = 'add';

    $this->__CloggyAcl->setUserRole('publisher');
    $this->__startupAcl();

    $userHasAccess = $this->__CloggyAcl->isUserCanAccessModule();
    $this->assertTrue($userHasAccess);

    $this->__Controller->params['action'] = 'test fake';
    $this->__CloggyAcl->setUserRole('publisher');
    $this->__startupAcl();

    $userHasAccessFake = $this->__CloggyAcl->isUserCanAccessModule();
    $this->assertTrue($userHasAccessFake);

    $this->__Controller->params['action'] = 'add';
    $this->__CloggyAcl->setUserRole('test_fake_role');
    $this->__startupAcl();

    $userHasAccessFake = $this->__CloggyAcl->isUserCanAccessModule();
    $this->assertFalse($userHasAccessFake);
  }

  public function testCallback() {

    $this->expectOutputString('failed!');
    $this->__Controller = new TestFakeController(new CakeRequest(), new CakeResponse());
    $this->__Controller->constructClasses();
    $this->__Controller->beforeFilter();
    $this->__Controller->CloggyAcl->startup($this->__Controller);
  }

  private function __startupAcl() {
    $this->__CloggyAcl->startup($this->__Controller);
  }

}