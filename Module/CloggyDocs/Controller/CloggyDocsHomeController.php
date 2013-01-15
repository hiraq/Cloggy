<?php

App::uses('CloggyAppController', 'Cloggy.Controller');

class CloggyDocsHomeController extends CloggyAppController {
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->deny('*');        
    }
    
    public function index() {
        $this->set('title_for_layout', 'CloggyDocs - Cloggy Documentation');
    }
    
    public function install() {
        
    }
    
}