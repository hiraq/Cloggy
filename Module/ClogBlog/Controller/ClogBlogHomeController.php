<?php

App::uses('ClogAppController','Clog.Controller');
App::uses('Sanitize', 'Utility');

class ClogBlogHomeController extends ClogAppController {
	
	public $uses = array(
		'ClogBlogPost',
		'ClogBlogCategory',
		'ClogBlogTag'		
	);
	
	public function beforeFilter() {
		
		parent::beforeFilter();
		$this->Auth->deny('*');
		
		$this->ClogModuleMenu->startup($this);
		$this->ClogModuleMenu->add('clog_blog',array(			
			'posts' => $this->_base.'/module/clog_blog/clog_blog_posts',
			'categories' => $this->_base.'/module/clog_blog/clog_blog_categories',
			'tags' => $this->_base.'/module/clog_blog/clog_blog_tags',
		));
		
		$this->set('moduleKeyMenus','clog_blog');
		
	}
	
	public function index() {
		
		$posts = $this->ClogBlogPost->getPosts(5,array('ClogNode.node_created' => 'desc'));
		$categories = $this->ClogBlogCategory->getCategories(5,array('ClogNode.node_created' => 'desc'));
		$tags = $this->ClogBlogTag->getTags(5,array('ClogNode.node_created' => 'desc'));
		
		$this->set('title_for_layout','Clog - ClogBlog Dashboard');
		$this->set(compact('posts','categories','tags'));
		
	}
	
}