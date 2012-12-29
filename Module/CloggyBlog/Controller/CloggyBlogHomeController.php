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
		
		$this->CloggyModuleMenu->startup($this);
		$this->CloggyModuleMenu->add('cloggy_blog',array(			
			'posts' => $this->_base.'/module/cloggy_blog/cloggy_blog_posts',
			'categories' => $this->_base.'/module/cloggy_blog/cloggy_blog_categories',
			'tags' => $this->_base.'/module/cloggy_blog/cloggy_blog_tags',
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