<?php

App::uses('CloggyAppController', 'Cloggy.Controller');

class CloggyDocsModuleController extends CloggyAppController {
    
    public function beforeFilter() {
        parent::beforeFilter();            
    }
    
    public function index() {
        $this->set('title_for_layout', 'CloggyDocs - Module');
    }
    
    public function create() {
        $this->set('title_for_layout', 'CloggyDocs - Create A Module');
    }
    
    public function activation() {
        $this->set('title_for_layout', 'CloggyDocs - Activate Module');
    }
    
}