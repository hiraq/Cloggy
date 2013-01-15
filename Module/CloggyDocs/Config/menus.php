<?php

/**
 * CloggyDocs Module - Menus configurations
 */
Configure::write('Cloggy.CloggyDocs.menus', array(    
    'module' => array(
        'Plugin' => array(
            'Installation' => cloggyUrlModule('cloggy_docs', 'cloggy_docs_home/install')            
        ),
        'Module' => array(
            'Installation' => cloggyUrlModule('cloggy_docs', 'cloggy_docs_module'),
            'Create' => cloggyUrlModule('cloggy_docs', 'cloggy_docs_module/create'),
        ),
        'Database' => array(
            'Nodes' => cloggyUrlModule('cloggy_docs', 'cloggy_docs_db'),
            'Model' => cloggyUrlModule('cloggy_docs', 'cloggy_docs_db/model'),
        )
    ),
    'sidebar' => array(
        'Documentation' => array(
            'Installation' => cloggyUrlModule('cloggy_docs', 'cloggy_docs_home/install'),
            'Module' => cloggyUrlModule('cloggy_docs', 'cloggy_docs_module'),
            'Database' => cloggyUrlModule('cloggy_docs', 'cloggy_docs_db')
        ),
        'Javascript' => array(
            'JQuery' => cloggyUrlModule('cloggy_docs', 'cloggy_docs_js/jquery'),
            'YepNope' => cloggyUrlModule('cloggy_docs', 'cloggy_docs_js/yepnope'),            
        ),
        'Assets/Webroot' => array(
            'CSS' => cloggyUrlModule('cloggy_docs', 'cloggy_docs_assets/css'),
            'Javascript' => cloggyUrlModule('cloggy_docs', 'cloggy_docs_assets/js'),            
        )
    )
));