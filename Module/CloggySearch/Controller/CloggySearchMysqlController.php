<?php

App::uses('CloggyAppController', 'Cloggy.Controller');

class CloggySearchMysqlController extends CloggyAppController {
    
    public $uses = array('CloggySearchFullText','CloggySearchLastUpdate');    
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->CloggySearchSchema = $this->Components->load('CloggySearchSchema');
    }    
    
    public function index() {  
        
        /*
         * setup pagination
         */
        $this->paginate = array(
            'limit' => 5,
            'order' => array('CloggySearchFullText.id' => 'desc')
        );
        
        $indexedTables = $this->paginate('CloggySearchFullText');
        $totalIndexed = $this->CloggySearchFullText->getTotal();
        $latestUpdate = $this->CloggySearchLastUpdate->getLatestUpdate('MysqlFullText','object_name');
                
        $this->set(compact('indexedTables','latestUpdate','totalIndexed'));
        $this->set('title_for_layout',__d('cloggy','Cloggy Search Management - MysqlFullText Search Engine'));
        
    }
    
    public function update() {
        
        //setup engine
        $this->CloggySearchSchema->engine('schema_mysqlfull_text');
        
        //get schema
        $schema = $this->CloggySearchSchema->getSchema();                
        
        //indexing data
        $this->CloggySearchFullText->updateIndex($schema);
        
    }
    
}