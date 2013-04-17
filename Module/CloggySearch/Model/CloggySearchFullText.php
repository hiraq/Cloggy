<?php

App::uses('CloggyAppModel', 'Cloggy.Model');

class CloggySearchFullText extends CloggyAppModel {
    
    public $name = 'CloggySearchFullText';
    public $useTable = 'search_fulltext';
    
    public function getTotal() {
        return $this->find('count');
    }
    
}