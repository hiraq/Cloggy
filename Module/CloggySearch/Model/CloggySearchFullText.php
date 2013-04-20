<?php

App::uses('CloggyAppModel', 'Cloggy.Model');
App::uses('CloggySearchFullTextIndex','Model/Behavior');

class CloggySearchFullText extends CloggyAppModel {       
    
    public $name = 'CloggySearchFullText';
    public $useTable = 'search_fulltext';
    public $actsAs = array('CloggySearchFullTexIndex');
    
    public function getTotal() {
        return $this->find('count');
    }       
    
}