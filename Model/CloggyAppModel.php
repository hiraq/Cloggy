<?php

class CloggyAppModel extends AppModel {

    public $actsAs = array('Containable');
    public $cacheQueries = true;      
    public $tablePrefix = 'cloggy_';
    
    public function getTables() {
        return $this->query('SHOW TABLES');
    }

}