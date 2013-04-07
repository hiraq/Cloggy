<?php

Configure::write('Cloggy.CloggySearch.menus', array(   
    'module' => array(        
        __d('cloggy','Engines') => array(
            __d('cloggy','Mysql') => CloggyCommon::urlModule('cloggy_search','cloggy_search_mysql'),            
            __d('cloggy','Sphinx') => CloggyCommon::urlModule('cloggy_search','cloggy_search_sphinx'),
            __d('cloggy','Solr') => CloggyCommon::urlModule('cloggy_search','cloggy_search_solr'),
        )
    ),
    'sidebar' => array(
        __d('cloggy','Update Index') => array(
            __d('cloggy','MySQL') => CloggyCommon::urlModule('cloggy_search','cloggy_search_mysql/update'),
            __d('cloggy','Sphinx') => CloggyCommon::urlModule('cloggy_search', 'cloggy_search_sphinx/update'),
            __d('cloggy','Solr') => CloggyCommon::urlModule('cloggy_search', 'cloggy_search_solr/update'),
        )
    )
));