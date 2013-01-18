<?php

Configure::write('Cloggy.CloggyUsers.menus', array(
    'sidebar' => array(
        'Create New' => array(
            'Manage' => CloggyCommon::urlModule('cloggy_users'),
            'Add User' => CloggyCommon::urlModule('cloggy_users', 'cloggy_users_home/add'),
        ),
        'Roles' => array(
            'Roles' => CloggyCommon::urlModule('cloggy_users','cloggy_users_role'),
            'Permissions' => CloggyCommon::urlModule('cloggy_users','cloggy_users_perm'),
            'Setup Roles' => CloggyCommon::urlModule('cloggy_users','cloggy_users_role/add'),
            'Setup Permission' => CloggyCommon::urlModule('cloggy_users','cloggy_users_perm/create'),
        )
    )
));