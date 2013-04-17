<?php

App::uses('CloggyAppController', 'Cloggy.Controller');

class CloggySearchMysqlController extends CloggyAppController {
    
    public $uses = array('CloggySearchFullText','CloggySearchLastUpdate');
    public $paginate = array(
        'CloggySearchFullText' => array(
            'limit' => 10,
            'order' => array('CloggySearchFullText.id' => 'desc')
        )
    );
    
    public function beforeFilter() {
        parent::beforeFilter();
    }
    
    public function index() {
        //pass
    }
    
    public function manage() {  
        
        $indexedTables = $this->paginate('CloggySearchFullText');
        $totalIndexed = $this->CloggySearchFullText->getTotal();
        $latestUpdate = $this->CloggySearchLastUpdate->getLatestUpdate('MysqlFullText','object_name');
                
        $this->set(compact('indexedTables','latestUpdate','totalIndexed'));
        $this->set('title_for_layout',__d('cloggy','Cloggy Search Management - MysqlFullText Search Engine'));
        
    }
    
}