<?php

App::uses('CloggyAppController', 'Cloggy.Controller');

class CloggySearchHomeController extends CloggyAppController {
    
    public function beforeFilter() {
        
        parent::beforeFilter();
        App::uses('CloggyModuleConfigReader', 'CustomConfigure');
        Configure::config('cloggy', new CloggyModuleConfigReader('CloggySearch'));
        
    }    
    
    public function index() {
        
        //load engines config
        Configure::load('engines','cloggy');
        
        $engines = Configure::read('Cloggy.CloggySearch.engines');        
        
        $this->set(compact('engines'));
        $this->set('title_for_layout', __d('cloggy','Cloggy - Cloggy Search Management'));
        
    }
    
}