<?php

App::uses('CloggySearchSchemaBase','CloggySearchSchema');

class CloggySearchSchemaMysql extends CloggySearchSchemaBase {
    
    public function __construct() {
        $this->_schema = array(
            'cloggy' => array(
                'table_prefix' => 'cloggy_',
                'tables' => array(
                    'node_contents' => array(
                        'primary_key' => 'id',
                        'field' => array(
                            'name' => 'content',
                            'format' => 'text'
                        ),
                        'limit' => 100
                    ),
                    'node_subjects' => array(
                        'primary_key' => 'id',
                        'field' => array(
                            'name' => 'subject',
                            'format' => 'sentences'
                        ),
                        'limit' => 100
                    )
                )
            )
        );
    }
    
    public function getSchema() {
        return $this->_schema;        
    }
    
}