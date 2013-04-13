<?php

App::uses('CloggyAppController', 'Cloggy.Controller');

class CloggySearchInstallController extends CloggyAppController {
    
    public $uses = array('CloggySearchInstall');
    
    public function beforeFilter() {
        
        parent::beforeFilter();               
        
        App::uses('CloggyModuleConfigReader', 'CustomConfigure');
        Configure::config('cloggy', new CloggyModuleConfigReader('CloggySearch'));
        
        //disable auto render
        $this->autoRender = false;  
    }
    
    public function index() {
                
        Configure::load('install','cloggy');                       
        $configs = Configure::read('Cloggy.CloggySearch.install');        
        $tables = $configs['tables'];                        
        
        
    }
    
}