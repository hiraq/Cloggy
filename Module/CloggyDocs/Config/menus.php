<?php

/**
 * CloggyDocs Module - Menus configurations
 */
Configure::write('Cloggy.CloggyDocs.menus', array(    
    'module' => array(
        'Plugin' => array(
            'About' => CloggyCommon::urlModule('cloggy_docs', 'cloggy_docs_home'),            
            'Installation' => CloggyCommon::urlModule('cloggy_docs', 'cloggy_docs_home/install'),
            'Contribute' => CloggyCommon::urlModule('cloggy_docs', 'cloggy_docs_home/contribute'),
            'License' => CloggyCommon::urlModule('cloggy_docs', 'cloggy_docs_home/license'),
            'Version' => CloggyCommon::urlModule('cloggy_docs', 'cloggy_docs_home/version'),
        ),
        'Module' => array(            
            'Module MVC' => CloggyCommon::urlModule('cloggy_docs', 'cloggy_docs_module'),
            'Create' => CloggyCommon::urlModule('cloggy_docs', 'cloggy_docs_module/create'),
            'Activation' => CloggyCommon::urlModule('cloggy_docs', 'cloggy_docs_module/activation'),
        ),
        'Database' => array(
            'Concept' => CloggyCommon::urlModule('cloggy_docs', 'cloggy_docs_db'),
            'Model/Behavior' => CloggyCommon::urlModule('cloggy_docs', 'cloggy_docs_db/model'),            
        ),
        'Users' => array(
            'User Managements' => CloggyCommon::urlModule('cloggy_docs','cloggy_docs_users'),
            'User Access' => CloggyCommon::urlModule('cloggy_docs','cloggy_docs_users/access')
        ),
        'UI' => array(
            'Management' => CloggyCommon::urlModule('cloggy_docs','cloggy_docs_ui'),
            'Menu config' => CloggyCommon::urlModule('cloggy_docs','cloggy_docs_ui/menus'),            
            'Javascript' => CloggyCommon::urlModule('cloggy_docs','cloggy_docs_ui/js')
        )        
    ),
    'sidebar' => array(
        'Basic' => array(
            'About' => CloggyCommon::urlModule('cloggy_docs', 'cloggy_docs_home'),
            'Version' => CloggyCommon::urlModule('cloggy_docs', 'cloggy_docs_home/version'),
            'License' => CloggyCommon::urlModule('cloggy_docs', 'cloggy_docs_home/license'),
        )
    )
));