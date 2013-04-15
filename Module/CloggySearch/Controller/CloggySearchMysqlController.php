<?php

App::uses('CloggyAppController', 'Cloggy.Controller');

class CloggySearchMysqlController extends CloggyAppController {
    
    public function beforeFilter() {
        parent::beforeFilter();
    }
    
    public function index() {
        //pass
    }
    
    public function manage() {
        $this->set('title_for_layout',__d('cloggy','Cloggy Search Management - MysqlFullText Search Engine'));
    }
    
}