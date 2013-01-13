<?php

Configure::write('Cloggy.CloggyUsers.menus',array(
    'sidebar' => array(
        'Create New' => array(
            'Manage' => cloggyUrlModule('cloggy_users'),
            'Add User' => cloggyUrlModule('cloggy_users', 'cloggy_users_home/add'),
        )
    )
));