<?php

class CloggyInstall extends CloggyAppModel {
    
    public $name = 'CloggyInstall';
    public $useTable = false;       
    
    public function isTableInstalled() {
        
        $tables = $this->getTables();
        if (!empty($tables)) {
            return in_array('cloggy_nodes',$tables);
        }
        
        return false;
        
    }
    
}