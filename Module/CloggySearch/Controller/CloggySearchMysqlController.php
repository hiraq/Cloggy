<?php

App::uses('CloggyAppController', 'Cloggy.Controller');

class CloggySearchMysqlController extends CloggyAppController {
    
    public $uses = array('CloggySearchFulltext');
    
    public function beforeFilter() {
        parent::beforeFilter();
    }
    
    public function index() {
        //pass
    }
    
    public function manage() {
        $tables = $this->CloggySearchFulltext->getTables();
        pr($tables);
        $this->set('title_for_layout',__d('cloggy','Cloggy Search Management - MysqlFullText Search Engine'));
    }
    
}