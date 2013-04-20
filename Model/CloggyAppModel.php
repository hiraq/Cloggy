<?php

class CloggyAppModel extends AppModel {

    public $actsAs = array('Containable');
    public $cacheQueries = true;      
    public $tablePrefix = 'cloggy_';
    
    /*
     * get all tables listed
     */
    public function getTables() {
        
        $queries = $this->query('SHOW TABLES');
        $tables = array();
        
        foreach($queries as $index => $value) {
            
            foreach($value['TABLE_NAMES'] as $key => $table) {
                $tables[] = $table;
            }
            
        }
        
        return $tables;
        
    }

}