<?php

App::uses('CloggyAppController', 'Cloggy.Controller');

class CloggySearchInstallController extends CloggyAppController {
    
    public $uses = array('CloggySearchInstall');
    
    public function beforeFilter() {
        parent::beforeFilter();
    }
    
    public function index() {
        
        //$this->autoRender = false;        
        echo 'test';
        
    }
    
}