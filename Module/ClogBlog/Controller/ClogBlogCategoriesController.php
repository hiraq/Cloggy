<?php

App::uses('ClogAppController','Clog.Controller');
App::uses('Sanitize','Utility');

class ClogBlogCategoriesController extends ClogAppController {
	
	public $uses = array(		
		'ClogBlogCategory',		
		'Clog.ClogValidation'
	);
	
	private $_user;
	
	public function beforeFilter() {
	
		parent::beforeFilter();
		$this->Auth->deny('*');
	
		$this->ClogModuleMenu->startup($this);
		$this->ClogModuleMenu->add('clog_blog',array(
			'Add New Category' => $this->_base.'/module/clog_blog/clog_blog_categories/add',
			'Manage Categories' => $this->_base.'/module/clog_blog/clog_blog_categories',
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
		
		$categories = $this->paginate('ClogBlogCategory');
		$this->set(compact('categories'));
		$this->set('title_for_layout','Clog - ClogBlog Categories');
		
	}
	
	public function add() {
		
		if($this->request->is('post')) {
			
			$this->request->data = Sanitize::clean($this->request->data);
			
			$dataToValidate = $this->request->data['ClogBlogCategories'];
			$checkCategoryExists = $this->ClogBlogCategory->isCategoryExists(
					$this->request->data['ClogBlogCategories']['category_name'],
					$this->_user['id']);
			
			$this->ClogValidation->set($dataToValidate);
			$this->ClogValidation->validate = array(
				'category_name' => array(
					'empty' => array(
						'rule' => 'notEmpty',
						'required' => true,
						'allowEmpty' => false,
						'message' => 'Category field required'
					),
					'exists' => array(
						'rule' => array('isValueEqual',$checkCategoryExists,false),
						'message' => 'Category has been exists'
					)
				)
			);
			
			if($this->ClogValidation->validates()) {
				
				$category = $this->request->data['ClogBlogCategories']['category_name'];
				
				$saved = $this->ClogBlogCategory->proceedCategories(array($category),$this->_user['id']);
				$savedId = $saved[0];
				
				/*
				 * set category parent
				 */
				if(isset($this->request->data['ClogBlogCategories']['category_parent'])) {

					if($this->request->data['ClogBlogCategories']['category_parent'] > 0) {

						$this->ClogBlogCategory->setCategoryParent(
								$this->request->data['ClogBlogCategories']['category_parent'],
								$savedId);
						
					}					
										
				}
				
				$this->set('success','Category has been saved.');
				
			}else{
				$this->set('errors',$this->ClogValidation->validationErrors);
			}
			
		}
		
		$categories = $this->ClogBlogCategory->getAllCategories();
		
		$this->set(compact('categories'));
		$this->set('title_for_layout','Clog - ClogBlog Add New Category');
		
	}
	
	public function edit($id=null) {
		
		if(is_null($id)) {
			$this->redirect($this->referer());
			exit();
		}
		
		$category = $this->ClogBlogCategory->getDetailCategory($id);
		
		if($this->request->is('post')) {
			
			$this->request->data = Sanitize::clean($this->request->data);
			$dataToValidate = array();
			
			$categoryName = $this->request->data['ClogBlogCategories']['category_name'];
			
			if(isset($this->request->data['ClogBlogCategories']['category_parent'])) {
				$categoryParent = $this->request->data['ClogBlogCategories']['category_parent'];
			}else{
				$categoryParent = 0;
			}
			
			$parent = $this->ClogBlogCategory->getParentCategory($id);
			
			if($categoryName != $category['ClogSubject']['subject']) {
				$dataToValidate['category_name'] = $categoryName;
			}
			
			if(!empty($dataToValidate)) {
				
				$checkCategoryExists = $this->ClogBlogCategory->isCategoryExists(
										$this->request->data['ClogBlogCategories']['category_name'],
										$this->_user['id']);								
				
				$this->ClogValidation->set($dataToValidate);
				$this->ClogValidation->validate = array(
					'category_name' => array(
						'empty' => array(
							'rule' => 'notEmpty',
							'required' => true,
							'allowEmpty' => false,
							'message' => 'Category field required'
						),
						'exists' => array(
							'rule' => array('isValueEqual',$checkCategoryExists,false),
							'message' => 'Category has been exists'
						)
					)
				);
				
				if($this->ClogValidation->validates()) {		
								
					$this->ClogBlogCategory->updateCategory($id,$categoryName);										
					if($parent['ClogNode']['id'] != $categoryParent && $categoryParent > 0) {
						$this->ClogBlogCategory->updateCategoryParent($id,$categoryParent);
					}
					
				}else{
					$this->set('errors',$this->ClogValidation->validationErrors);
				}
				
			}
			
			if($parent['ClogNode']['id'] != $categoryParent && $categoryParent > 0) {
				$this->ClogBlogCategory->updateCategoryParent($id,$categoryParent);
			}
			
			$this->set('success','Your category has been updated.');
			
		}
					
		$categories = $this->ClogBlogCategory->getAllCategories($id);		
		
		$this->set(compact('categories','category','id'));
		$this->set('title_for_layout','Clog - ClogBlog Edit Category');
		
	}
	
	public function remove($id=null) {
		
		if(is_null($id)) {
			$this->redirect($this->referer());
			exit();
		}
		
		$this->ClogBlogCategory->deleteCategory($id);
		$this->redirect($this->referer());
		
	}
	
}