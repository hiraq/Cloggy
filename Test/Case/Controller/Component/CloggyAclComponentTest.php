<?php

App::uses('Controller', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('ComponentCollection', 'Controller');
App::uses('CloggyAclComponent', 'Cloggy.Controller/Component');

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
        $this->assertFalse(empty($this->__Controller));
        $this->assertFalse(empty($this->__CloggyAcl));
    }    

}