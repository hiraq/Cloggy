<?php

App::uses('CloggyAppController', 'Cloggy.Controller');

class CloggyDocsDbController extends CloggyAppController {
    
    public function beforeFilter() {
        parent::beforeFilter();                                                                                              
    }                
    
    public function index() {           
        $this->set('title_for_layout', 'CloggyDocs - Database');
    }
    
    public function model() {
        $this->set('title_for_layout', 'CloggyDocs - Model And Behavior');
    }
    
}