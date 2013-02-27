<?php

App::uses('CloggyAppController', 'Cloggy.Controller');
App::uses('Xml','Utility');
App::uses('Hash','Utility');

class CloggyBlogImportController extends CloggyAppController {
    
    public $uses = array(
        'CloggyBlogPost',
        'CloggyBlogCategory',
        'CloggyBlogTag',
        'CloggyBlogMedia',
        'Cloggy.CloggyValidation'
    );  
    
    private $__CloggyFileUpload;
    
    public function beforeFilter() {
        
        parent::beforeFilter();   
        $this->__CloggyFileUpload = $this->Components->load('Cloggy.CloggyFileUpload');
        
    }
    
    public function index() {
        //pass
    }
    
    public function wordpress() {
        
        if ($this->request->is('post')) {
            
            /*
             * validate file upload
             */
            $this->CloggyValidation->set($this->request->data['CloggyBlogImport']);
            $this->CloggyValidation->validate = array(
                'wordpress_xml' => array(
                    'ext' => array(
                        'rule' => array('extension',array('xml')),
                        'message' => 'You must upload xml file'
                    ),
                    'required' => array(
                        'rule' => 'notEmpty',
                        'message' => 'You must fill file field.'
                    )
                )
            );
            
            if ($this->CloggyValidation->validates()) {
                
                
                
            } else {
                $this->set('errors', $this->CloggyValidation->validationErrors);
            }
            
        }
        
        $this->set('title_for_layout','Import Post From Wordpress');
        
    }
    
}