<?php

App::uses('CloggyAppController', 'Cloggy.Controller');
App::uses('Sanitize', 'Utility');

class CloggyUsersPermController extends CloggyAppController {
    
    public $uses = array(
        'Cloggy.CloggyUser', 
        'Cloggy.CloggyUserRole',
        'Cloggy.CloggyValidation'
    );        

    public function beforeFilter() {

        parent::beforeFilter();
        $this->Auth->deny('*');

        //load additional helpers        
        $this->helpers[] = 'Form';

        //setup pagination
        $this->paginate = array(
            'CloggyUserRole' => array(
                'limit' => 10,
                'contain' => false,
                'order' => array('role_name' => 'asc')
            )
        );
        
    }
    
    public function index() {
        
    }
    
}