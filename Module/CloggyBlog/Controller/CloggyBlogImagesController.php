<?php

App::uses('CloggyAppController', 'Cloggy.Controller');

class CloggyBlogImagesController extends CloggyAppController {
    
    public $uses = array(
        'CloggyBlogPost',        
        'CloggyBlogMedia',
        'Cloggy.CloggyValidation'
    );    
    
    public function beforeFilter() {
        parent::beforeFilter();  
        $this->helpers[] = 'CloggyBlogAsset';
    }
    
    public function index() {
        echo 'test';exit();
    }
    
}