<?php

/**
 * CloggyDocs Module - Menus configurations
 */
Configure::write('Cloggy.CloggyDocs.menus', array(    
    'module' => array(
        __d('cloggy','Plugin') => array(
            __d('cloggy','About') => CloggyCommon::urlModule('cloggy_docs', 'cloggy_docs_home'),            
            __d('cloggy','Installation') => CloggyCommon::urlModule('cloggy_docs', 'cloggy_docs_home/install'),            
            __d('cloggy','License') => CloggyCommon::urlModule('cloggy_docs', 'cloggy_docs_home/license'),
            __d('cloggy','Version') => CloggyCommon::urlModule('cloggy_docs', 'cloggy_docs_home/version'),
        ),
        __d('cloggy','Module') => array(            
            __d('cloggy','Module MVC') => CloggyCommon::urlModule('cloggy_docs', 'cloggy_docs_module'),
            __d('cloggy','Create') => CloggyCommon::urlModule('cloggy_docs', 'cloggy_docs_module/create'),
            __d('cloggy','Installation') => CloggyCommon::urlModule('cloggy_docs', 'cloggy_docs_module/install'),
            __d('cloggy','Activation') => CloggyCommon::urlModule('cloggy_docs', 'cloggy_docs_module/activation'),
        ),
        __d('cloggy','Database') => array(
            __d('cloggy','Concept') => CloggyCommon::urlModule('cloggy_docs', 'cloggy_docs_db'),
            __d('cloggy','Model/Behavior') => CloggyCommon::urlModule('cloggy_docs', 'cloggy_docs_db/model'),            
        ),
        __d('cloggy','Users') => array(
            __d('cloggy','User Managements') => CloggyCommon::urlModule('cloggy_docs','cloggy_docs_users'),
            __d('cloggy','User Access') => CloggyCommon::urlModule('cloggy_docs','cloggy_docs_users/access')
        ),
        __d('cloggy','UI') => array(
            __d('cloggy','Management') => CloggyCommon::urlModule('cloggy_docs','cloggy_docs_ui'),
            __d('cloggy','Menu config') => CloggyCommon::urlModule('cloggy_docs','cloggy_docs_ui/menus'),            
            __d('cloggy','Javascript') => CloggyCommon::urlModule('cloggy_docs','cloggy_docs_ui/js')
        )        
    ),
    'sidebar' => array(
        __d('cloggy','Basic') => array(
            __d('cloggy','About') => CloggyCommon::urlModule('cloggy_docs', 'cloggy_docs_home'),
            __d('cloggy','Version') => CloggyCommon::urlModule('cloggy_docs', 'cloggy_docs_home/version'),
            __d('cloggy','License') => CloggyCommon::urlModule('cloggy_docs', 'cloggy_docs_home/license'),
        )
    )
));