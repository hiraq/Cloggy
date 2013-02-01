<?php

App::uses('CloggyAppController', 'Cloggy.Controller');

class CloggyDocsUiController extends CloggyAppController {
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->deny('*');                                                                                         
    }   
    
    public function index() {
        $this->set('title_for_layout', 'CloggyDocs - UI Management');
    }
    
}