<?php

/**
 * CloggyDocs Module - Menus configurations
 */
Configure::write('Cloggy.CloggyDocs.menus', array(    
    'module' => array(
        'Plugin' => array(
            'About' => cloggyUrlModule('cloggy_docs', 'cloggy_docs_home'),            
            'Installation' => cloggyUrlModule('cloggy_docs', 'cloggy_docs_home/install'),
            'Contribute' => cloggyUrlModule('cloggy_docs', 'cloggy_docs_home/contribute'),
            'License' => cloggyUrlModule('cloggy_docs', 'cloggy_docs_home/license'),
            'Version' => cloggyUrlModule('cloggy_docs', 'cloggy_docs_home/version'),
        ),
        'Module' => array(
            'Installation' => cloggyUrlModule('cloggy_docs', 'cloggy_docs_module'),
            'Create' => cloggyUrlModule('cloggy_docs', 'cloggy_docs_module/create'),
            'Module MVC' => cloggyUrlModule('cloggy_docs', 'cloggy_docs_module/mvc'),
        ),
        'Database' => array(
            'Nodes' => cloggyUrlModule('cloggy_docs', 'cloggy_docs_db')            
        ),
        'Users' => array(
            'User Managements' => cloggyUrlModule('cloggy_docs','cloggy_docs_users'),
            'User Access' => cloggyUrlModule('cloggy_docs','cloggy_docs_users/access')
        ),
        'UI' => array(
            'Menu config' => cloggyUrlModule('cloggy_docs','cloggy_docs_ui/menus'),
            'Elements' => cloggyUrlModule('cloggy_docs','cloggy_docs_ui/elements'),
            'Javascript' => cloggyUrlModule('cloggy_docs','cloggy_docs_ui/js')
        )        
    ),
    'sidebar' => array(
        'Basic' => array(
            'About' => cloggyUrlModule('cloggy_docs', 'cloggy_docs_home'),
            'Version' => cloggyUrlModule('cloggy_docs', 'cloggy_docs_home/version'),
            'License' => cloggyUrlModule('cloggy_docs', 'cloggy_docs_home/license'),
        )
    )
));