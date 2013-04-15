<?php

Configure::write('Cloggy.CloggySearch.engines',array(
    'engines' => array('MysqlFullText','SphinxSearch','ApacheSolr'),
    'controllers' => array(
        'MysqlFullText' => 'CloggySearchMysql',
        'SphinxSearch' => 'CloggySearchSphinx',
        'ApacheSolr' => 'CloggySearchSolr'
    )
));