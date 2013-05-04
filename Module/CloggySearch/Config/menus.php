<?php

Configure::write('Cloggy.CloggySearch.menus', array(   
    'module' => array(        
        __d('cloggy','Engines') => array(
            __d('cloggy','Mysql') => CloggyCommon::urlModule('cloggy_search','cloggy_search_mysql'),                        
        )
    ),
    'sidebar' => array(
        __d('cloggy','About') => array(
            __d('cloggy','MySQL') => CloggyCommon::urlModule('cloggy_search','cloggy_search_mysql/help'),            
        )
    )
));