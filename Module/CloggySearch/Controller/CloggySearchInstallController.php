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
                
        /*
         * get table installation
         */
        Configure::load('install','cloggy');                       
        $configs = Configure::read('Cloggy.CloggySearch.install');        
        $searchTablesQuery = $configs['tables'];                                        
        $tables = $this->CloggySearchInstall->query('SHOW TABLES');
        
        /*
         * compare with main tables cloggy
         */
        $cloggyTables = array();
        foreach($tables as $table) {            
            $name = array_values($table['TABLE_NAMES']);            
            $cloggyTables[] = $name[0];
        }
        
        if (!empty($cloggyTables)) {
            
            /*
             * create search tables
             */
            $createdTables = array();
            foreach($searchTablesQuery as $searchTable => $query) {
            
                if (!in_array($searchTable,$cloggyTables)) {
                    $created = $this->CloggySearchInstall->query($query);
                    $createdTables[$searchTable] = $created;                    
                }

            }
            
            //title page
            $this->set('title_for_layout',__d('cloggy','Cloggy Search Module - Installation'));
            
            //send to view
            $this->set(compact('createdTables'));
            
            //rendering view
            $this->render('index');
            
        } else {
            
            //title page
            $this->set('title_for_layout',__d('cloggy','Cloggy Search Module - Installation - Error'));
            
            $this->set('install_error',__d('cloggy','Cloggy main tables not found.'));
        }                   
        
    }
    
}