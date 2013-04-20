<?php

App::uses('ModelBehavior','Model');

class CloggySearchFullTexIndexBehavior extends ModelBehavior {
    
     /*
     * options for indexing tables
     */
    private $__updateGroups = array();
    private $__indexedSchema;
    private $__tablesToIndex;
    private $__prefixToIndex;
    
    //used model
    private $__model;
    
     /**
     * Update MysqlFullText data index
     * @param array $schema
     */
    public function updateIndex(Model $model,$schema) {
        
        if (is_array($schema) && !empty($schema)) {
            
            /*
             * setup groups
             */
            foreach($schema as $group => $options) {
                $this->__updateGroups[] = $group;
            }    
            
            //setup schema
            $this->__indexedSchema = $schema;
            
            //requested model
            $this->__model = $model;
            
            //run indexer
            $this->__runIndex();
            
        }
        
    }
    
    /**
     * Run index
     */
    private function __runIndex() {
        
        if (!empty($this->__updateGroups)) {
            
            foreach($this->__updateGroups as $group) {
                
                if (array_key_exists($group,$this->__indexedSchema)) {                                        
                    
                    //setup table to index
                    $this->__tablesToIndex = $this->__indexedSchema[$group]['tables'];
                    
                    //setup table prefix (optional)
                    if (isset($this->__indexedSchema[$group]['table_prefix'])) {
                        $this->__prefixToIndex = $this->__indexedSchema[$group]['table_prefix'];
                    }
                    
                    //index tables
                    $this->__indexingTables();
                    
                }
                
            }                        
            
        }
        
    }
    
    /**
     * Index tables
     */
    private function __indexingTables() {
        
        if (!empty($this->__tablesToIndex)) {
            
            foreach($this->__tablesToIndex as $table => $options) {
               
                /*
                 * building table properties
                 */
                $tableName = $this->__prefixToIndex.$table;
                $fieldName = $options['field']['name'];
                $fieldFormat = $options['field']['format'];
                $limit = isset($options['limit']) && !empty($options['limit']) ? $options['limit'] : 10;        
                $primaryKey = $options['primary_key'];
                
                /*
                 * get data to index
                 */
                $query = $this->__querySelect(compact('fieldName','limit','primaryKey','tableName'));
                $data = $this->__model->query($query);
                
                /*
                 * data parsed
                 */
                if ($data) {
                    
                    
                    
                }
                
            }
            
        }
        
    }
    
    /**
     * Database query to get all data from requested table
     * @param array $params
     * @return string
     */
    private function __querySelect($params) {
               
        if (is_array($params) && !empty($params)) {
            extract($params);
        }
        
        return 'SELECT '.$fieldName.','.$primaryKey.' FROM '.$tableName.' ORDER BY '.$primaryKey.' DESC LIMIT '.$limit;
        
    }
    
}