<?php

Configure::write('Cloggy.CloggyUsers.menus', array(   
    'module' => array(        
        'Roles' => array(
            'Manage' => CloggyCommon::urlModule('cloggy_users','cloggy_users_role'),            
            'Create' => CloggyCommon::urlModule('cloggy_users','cloggy_users_role/add'),            
        ),
        'Permissions' => array(
            'Manage' => CloggyCommon::urlModule('cloggy_users','cloggy_users_perm'),
            'Setup' => CloggyCommon::urlModule('cloggy_users','cloggy_users_perm/create'),
        )
    ),
    'sidebar' => array(
        'Create New' => array(
            'Manage' => CloggyCommon::urlModule('cloggy_users'),
            'Add User' => CloggyCommon::urlModule('cloggy_users', 'cloggy_users_home/add'),
        ),
        'User Access' => array(
            'Roles' => CloggyCommon::urlModule('cloggy_users','cloggy_users_role'),
            'Permissions' => CloggyCommon::urlModule('cloggy_users','cloggy_users_perm'),            
        ),
    )
));