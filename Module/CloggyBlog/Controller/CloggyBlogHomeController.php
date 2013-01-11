<?php

App::uses('CloggyAppController','Cloggy.Controller');
App::uses('Sanitize', 'Utility');

class CloggyBlogHomeController extends CloggyAppController {
	
	public $uses = array(
		'CloggyBlogPost',
		'CloggyBlogCategory',
		'CloggyBlogTag'		
	);		
	
	public function beforeFilter() {
		
		parent::beforeFilter();
		$this->Auth->deny('*');
		
		$this->CloggyModuleMenu->add('module',array(			
			'Posts' => array(
				'Manage' => $this->_base.'/module/cloggy_blog/cloggy_blog_posts',
				'Add' => $this->_base.'/module/cloggy_blog/cloggy_blog_posts/add',
			),
			'Categories' => array(
				'Manage' => $this->_base.'/module/cloggy_blog/cloggy_blog_categories',
				'Add' => $this->_base.'/module/cloggy_blog/cloggy_blog_categories/add',
			),
			'Tags' => array(
				'Manage' => $this->_base.'/module/cloggy_blog/cloggy_blog_tags',
				'Add' => $this->_base.'/module/cloggy_blog/cloggy_blog_tags/add',
			)
		));	

		$this->CloggyModuleMenu->setGroup('Create New',array(
			'Add Post' => $this->CloggyModuleMenu->urlModule('cloggy_blog','cloggy_blog_posts/add'),
			'Add Category' => $this->CloggyModuleMenu->urlModule('cloggy_blog','cloggy_blog_categories/add'),
			'Add Tags' => $this->CloggyModuleMenu->urlModule('cloggy_blog','cloggy_blog_tags/add')
		));
		
		$this->set('moduleKeyMenus','cloggy_blog');				
			
	}
	
	public function index() {
		
		$posts = $this->CloggyBlogPost->getPosts(5,array('CloggyNode.node_created' => 'desc'));
		$categories = $this->CloggyBlogCategory->getCategories(5,array('CloggyNode.node_created' => 'desc'));
		$tags = $this->CloggyBlogTag->getTags(5,array('CloggyNode.node_created' => 'desc'));
		
		$this->set('title_for_layout','Cloggy - CloggyBlog Dashboard');
		$this->set(compact('posts','categories','tags'));
		
	}	
	
}