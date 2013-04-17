<?php

App::uses('CloggyAppModel', 'Cloggy.Model');

class CloggySearchLastUpdate extends CloggyAppModel {
    
    public $name = 'CloggySearchLastUpdate';
    public $useTable = 'search_last_update';
    
    public function getLatestUpdate($engine,$field) {
        
        $data = $this->find('first',array(
            'conditions' => array('CloggySearchLastUpdate.engine' => $engine),
            'field' => array('CloggySearchLastUpdate.'.$field)
        ));
        
        return !empty($data) ? $data['CloggySearchLastUpdate'][$field] : __d('cloggy','None');
        
    }
    
}