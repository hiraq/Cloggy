<?php

App::uses('CloggyAppController', 'Cloggy.Controller');

class CloggySearchInstallController extends CloggyAppController {
    
    public function beforeFilter() {
        parent::beforeFilter();
    }
    
    public function index() {
        
        //disable rendering views
        $this->autoRender = false;
        
    }
    
}