<?php

Configure::write('Cloggy.CloggyUsers.menus', array(
    'sidebar' => array(
        'Create New' => array(
            'Manage' => CloggyCommon::urlModule('cloggy_users'),
            'Add User' => CloggyCommon::urlModule('cloggy_users', 'cloggy_users_home/add'),
        )
    )
));