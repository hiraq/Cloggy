<?php

App::uses('CloggyAppController', 'Cloggy.Controller');

class CloggySearchHomeController extends CloggyAppController {
    
    public function beforeFilter() {
        parent::beforeFilter();
    }
    
    public function index() {
        $this->set('title_for_layout', __d('cloggy','Cloggy - Cloggy Search Management'));
    }
    
}