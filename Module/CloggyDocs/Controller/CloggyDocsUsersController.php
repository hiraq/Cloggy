<?php

App::uses('CloggyAppController', 'Cloggy.Controller');

class CloggyDocsUsersController extends CloggyAppController {
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->deny('*');                                                                                         
    }   
    
    public function index() {
        $this->set('title_for_layout', 'CloggyDocs - User Management');
    }
    
    public function access() {
        $this->set('title_for_layout', 'CloggyDocs - User Access');
    }
    
}