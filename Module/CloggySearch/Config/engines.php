<?php

Configure::write('Cloggy.CloggySearch.engines',array(
    'engines' => array('MysqlFullText'),
    'controllers' => array(
        'MysqlFullText' => 'CloggySearchMysql'        
    )
));