<?php

App::uses('ClogAppController','Clog.Controller');
App::uses('Sanitize','Utility');

class ClogBlogTagsController extends ClogAppController {
	
	public $uses = array(
		'ClogBlogTag',
		'Clog.ClogValidation'
	);
	
	private $_user;
	
	public function beforeFilter() {
	
		parent::beforeFilter();
		$this->Auth->deny('*');
	
		$this->ClogModuleMenu->startup($this);
		$this->ClogModuleMenu->add('clog_blog',array(
			'Add New Tag' => $this->_base.'/module/clog_blog/clog_blog_tags/add',
			'Manage Tags' => $this->_base.'/module/clog_blog/clog_blog_tags',
			'Manage Blog' => $this->_base.'/module/clog_blog',
		));
	
		$this->set('moduleKeyMenus','clog_blog');
	
		$this->_user = $this->Auth->user();
	
	}
	
	public function index() {
		
		$this->paginate = array(
			'ClogBlogCategory' => array(
				'limit' => 10,
				'contain' => false,
				'fields' => array(
					'ClogNode.id',
					'ClogNode.node_created',
					'ClogNode.node_status'
				)
			)
		);
		
		$tags = $this->paginate('ClogBlogTag');
		$this->set(compact('tags'));
		$this->set('title_for_layout','Clog - ClogBlog Tags');
		
	} 
	
	public function add() {
				
		if($this->request->is('post')) {
				
			$this->request->data = Sanitize::clean($this->request->data);
				
			$dataToValidate = $this->request->data['ClogBlogTags'];
			$checkTagExists = $this->ClogBlogTag->isTagExists(
					$this->request->data['ClogBlogTags']['tag_name'],
					$this->_user['id']);
				
			$this->ClogValidation->set($dataToValidate);
			$this->ClogValidation->validate = array(
				'tag_name' => array(
					'empty' => array(
						'rule' => 'notEmpty',
						'required' => true,
						'allowEmpty' => false,
						'message' => 'Tag field required'
					),
					'exists' => array(
						'rule' => array('isValueEqual',$checkTagExists,false),
						'message' => 'Category has been exists'
					)
				)
			);
				
			if($this->ClogValidation->validates()) {
		
				$tag = $this->request->data['ClogBlogTags']['tag_name'];
		
				$saved = $this->ClogBlogTag->proceedTags(array($tag),$this->_user['id']);											
				$this->set('success','Tag has been saved.');
		
			}else{
				$this->set('errors',$this->ClogValidation->validationErrors);
			}
				
		}
		$this->set('title_for_layout','Clog - ClogBlog Add New Tag');
		
	}
	
	public function edit($id=null) {
		
		if(is_null($id)) {
			$this->redirect($this->referer());
			exit();
		}
				
		$tag = $this->ClogBlogTag->getDetailTag($id);
		
		if($this->request->is('post')) {
				
			$this->request->data = Sanitize::clean($this->request->data);
			$dataToValidate = array();
				
			$tagName = $this->request->data['ClogBlogTags']['tag_name'];						
				
			if($tagName != $tag['ClogSubject']['subject']) {
				$dataToValidate['tag_name'] = $tagName;
			}
				
			if(!empty($dataToValidate)) {
		
				$checkTagExists = $this->ClogBlogTag->isTagExists(
					$this->request->data['ClogBlogTags']['tag_name'],
					$this->_user['id']);
		
				$this->ClogValidation->set($dataToValidate);
				$this->ClogValidation->validate = array(
					'tag_name' => array(
						'empty' => array(
							'rule' => 'notEmpty',
							'required' => true,
							'allowEmpty' => false,
							'message' => 'Tag field required'
						),
						'exists' => array(
							'rule' => array('isValueEqual',$checkTagExists,false),
							'message' => 'Tag has been exists'
						)
					)
				);
		
				if($this->ClogValidation->validates()) {		
					$this->ClogBlogTag->updateTag($id,$tagName);											
				}else{
					$this->set('errors',$this->ClogValidation->validationErrors);
				}
		
			}							
				
			$tag = $this->ClogBlogTag->getDetailTag($id);					
			$this->set('success','Your tag has been updated.');
				
		}									
				
		$this->set(compact('tag','id'));
		$this->set('title_for_layout','Clog - ClogBlog Edit Tag');
		
	}
	
	public function remove($id=null) {
		
		if(is_null($id)) {
			$this->redirect($this->referer());
			exit();
		}
		
		$this->ClogBlogTag->deleteTag($id);
		$this->redirect($this->referer());
		
	}
	
}