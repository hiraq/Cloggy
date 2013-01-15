<?php

Configure::write('Cloggy.ModuleTest.menus', array(
    'module' => array(
        'test' => '/test',
        'test2' => '/test2'
    ),
    'sidebar' => array(
        'test_sidebar' => array(
            'Test1' => 'test/index',
            'Test2' => 'test2/index'
        )
    )
));