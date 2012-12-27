<?php

App::uses('ClogAppController','Clog.Controller');

class ClogBlogAjaxController extends ClogAppController {
	
	public $uses = array(
		'ClogBlogPost',
		'ClogBlogCategory',
		'ClogBlogTag'		
	);
	
	private $_user;
	
	public function beforeFilter() {
	
		parent::beforeFilter();
		$this->Auth->deny('*');							
		$this->_user = $this->Auth->user();
		
		if(!$this->request->is('ajax')) {
			$this->redirect('/');
		}
		
		$this->autoRender = false;
	
	}
	
	public function delete_all_posts() {
		
		$posts = $this->request->data['post'];
		foreach($posts as $post) {
			$this->ClogBlogPost->deletePost($post,false);
		}
		
		echo json_encode(array('msg' => 'success'));
		
	}
	
	public function draft_all_posts() {
		
		$posts = $this->request->data['post'];
		foreach($posts as $post) {
			$this->ClogBlogPost->updatePostStat($post,0);
		}
		
		echo json_encode(array('msg' => 'success'));
		
	}
	
	public function publish_all_posts() {
		
		$posts = $this->request->data['post'];
		foreach($posts as $post) {
			$this->ClogBlogPost->updatePostStat($post,1);
		}
		
		echo json_encode(array('msg' => 'success'));
		
	}
	
	public function delete_all_categories() {
		
		$categories = $this->request->data['category'];
		foreach($categories as $category) {
			$this->ClogBlogCategory->deleteCategory($category);
		}
		
		echo json_encode(array('msg' => 'success'));
		
	}
	
	public function delete_all_tags() {
		
		$tags = $this->request->data['tag'];
		foreach($tags as $tag) {
			$this->ClogBlogTag->deleteTag($tag);
		}
		
		echo json_encode(array('msg' => 'success'));
		
	}
	
}