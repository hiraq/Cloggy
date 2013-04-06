<?php

Configure::write('Cloggy.CloggyUsers.menus', array(   
    'module' => array(        
        __d('cloggy','Roles') => array(
            __d('cloggy','Manage') => CloggyCommon::urlModule('cloggy_users','cloggy_users_role'),            
            __d('cloggy','Create') => CloggyCommon::urlModule('cloggy_users','cloggy_users_role/add'),            
        ),
        __d('cloggy','Permissions') => array(
            __d('cloggy','Manage') => CloggyCommon::urlModule('cloggy_users','cloggy_users_perm'),
            __d('cloggy','Setup') => CloggyCommon::urlModule('cloggy_users','cloggy_users_perm/create'),
        )
    ),
    'sidebar' => array(
        __d('cloggy','Users') => array(
            __d('cloggy','Manage') => CloggyCommon::urlModule('cloggy_users'),
            __d('cloggy','Add User') => CloggyCommon::urlModule('cloggy_users', 'cloggy_users_home/add'),
        ),
        __d('cloggy','User Access') => array(
            __d('cloggy','Roles') => CloggyCommon::urlModule('cloggy_users','cloggy_users_role'),
            __d('cloggy','Permissions') => CloggyCommon::urlModule('cloggy_users','cloggy_users_perm'),            
        ),
    )
));