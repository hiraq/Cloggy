<?php

/**
 * CloggyBlog Module - Menus configurations
 */
Configure::write('Cloggy.CloggyBlog.menus', array(
    'module' => array(
        'Posts' => array(
            'Manage' => CloggyCommon::urlModule('cloggy_blog', 'cloggy_blog_posts'),
            'Add' => CloggyCommon::urlModule('cloggy_blog', 'cloggy_blog_posts/add'),
        ),
        'Categories' => array(
            'Manage' => CloggyCommon::urlModule('cloggy_blog', 'cloggy_blog_categories'),
            'Add' => CloggyCommon::urlModule('cloggy_blog', 'cloggy_blog_categories/add'),
        ),
        'Tags' => array(
            'Manage' => CloggyCommon::urlModule('cloggy_blog', 'cloggy_blog_tags'),
            'Add' => CloggyCommon::urlModule('cloggy_blog', 'cloggy_blog_tags/add'),
        )
    ),
    'sidebar' => array(
        'Create New' => array(
            'Add Post' => CloggyCommon::urlModule('cloggy_blog', 'cloggy_blog_posts/add'),
            'Add Category' => CloggyCommon::urlModule('cloggy_blog', 'cloggy_blog_categories/add'),
            'Add Tags' => CloggyCommon::urlModule('cloggy_blog', 'cloggy_blog_tags/add'),
            'WordPress Importer' => CloggyCommon::urlModule('cloggy_blog', 'cloggy_blog_import/wordpress')
        )
    )
));