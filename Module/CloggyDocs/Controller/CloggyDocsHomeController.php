<?php

App::uses('CloggyAppController', 'Cloggy.Controller');

class CloggyDocsHomeController extends CloggyAppController {
    
    public function beforeFilter() {
        parent::beforeFilter();              
    }
    
    public function index() {
        $this->set('title_for_layout', 'CloggyDocs - Cloggy Documentation');
    }
    
    public function install() {
        $this->set('title_for_layout','CloggyDocs - Installation');
    }
    
    public function version() {
        $this->set('title_for_layout','CloggyDocs - Versioning');
    }
    
    public function license() {
        $this->set('title_for_layout','CloggyDocs - License');
    }
    
    public function contribute() {
        $this->set('title_for_layout','CloggyDocs - Contribute');
    }
    
}