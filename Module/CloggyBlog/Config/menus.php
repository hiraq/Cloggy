<?php

/**
 * CloggyBlog Module - Menus configurations
 */
Configure::write('Cloggy.CloggyBlog.menus', array(
    'module' => array(
        'Posts' => array(
            'Manage' => cloggyUrlModule('cloggy_blog', 'cloggy_blog_posts'),
            'Add' => cloggyUrlModule('cloggy_blog', 'cloggy_blog_posts/add'),
        ),
        'Categories' => array(
            'Manage' => cloggyUrlModule('cloggy_blog', 'cloggy_blog_categories'),
            'Add' => cloggyUrlModule('cloggy_blog', 'cloggy_blog_categories/add'),
        ),
        'Tags' => array(
            'Manage' => cloggyUrlModule('cloggy_blog', 'cloggy_blog_tags'),
            'Add' => cloggyUrlModule('cloggy_blog', 'cloggy_blog_tags/add'),
        )
    ),
    'sidebar' => array(
        'Create New' => array(
            'Add Post' => cloggyUrlModule('cloggy_blog', 'cloggy_blog_posts/add'),
            'Add Category' => cloggyUrlModule('cloggy_blog', 'cloggy_blog_categories/add'),
            'Add Tags' => cloggyUrlModule('cloggy_blog', 'cloggy_blog_tags/add')
        )
    )
));