<?php

/*
 * cloggy schema configurations
 */
$config = array(
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
);

//write config
Configure::write('Cloggy.CloggySearch.schema_mysqlfull_text',array(
    'cloggy' => $config
));