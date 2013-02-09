<?php

App::uses('CloggyAppController', 'Cloggy.Controller');

class CloggyDocsUiController extends CloggyAppController {
    
    public function beforeFilter() {
        parent::beforeFilter();                                                                                               
    }   
    
    public function index() {
        $this->set('title_for_layout', 'CloggyDocs - UI Management');
    }
    
    public function menus() {
        $this->set('title_for_layout', 'CloggyDocs - Menus Management');
    }
    
    public function js() {
        $this->set('title_for_layout', 'CloggyDocs - Javascript Management');
    }
    
}