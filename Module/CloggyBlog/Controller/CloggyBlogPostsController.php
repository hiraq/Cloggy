<?php

App::uses('CloggyAppController','Cloggy.Controller');
App::uses('Sanitize','Utility');

class CloggyBlogPostsController extends CloggyAppController {
	
	public $uses = array(		
		'CloggyBlogPost',
		'CloggyBlogCategory',
		'CloggyBlogTag',
		'Cloggy.CloggyValidation'
	);		
	
	private $_user;
	
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
		
		$this->_user = $this->Auth->user();						
	
	}
	
	public function index() {
		
		$this->paginate = array(
			'CloggyBlogPost' => array(
				'limit' => 10,
				'contain' => false,				
				'fields' => array(
					'CloggyNode.id',
					'CloggyNode.node_created',
					'CloggyNode.node_status'
				)
			)
		);
		
		$posts = $this->paginate('CloggyBlogPost');		
		$this->set(compact('posts'));
		
	}
	
	public function add() {				
		
		$categories = $this->CloggyBlogCategory->getAllCategories();		
		$tags = $this->CloggyBlogTag->getAllTags();				
				
		if($this->request->is('post')) {
						
			$dataValidate = $this->request->data['CloggyBlogPost'];
			
			/*
			 * custom validation post title
			 */			
			$checkPostSubject = $this->CloggyBlogPost->isTitleExists($this->request->data['CloggyBlogPost']['title'],$this->_user['id']);
			
			$this->CloggyValidation->set($dataValidate);
			$this->CloggyValidation->validate = array(
				'title' => array(
					'empty' => array(
						'rule' => 'notEmpty',
						'required' => true,
						'allowEmpty' => false,
						'message' => 'Title field required'
					),
					'exists' => array(
						'rule' => array('isValueEqual',$checkPostSubject,false),
						'message' => 'The title, <strong>"'.$this->request->data['CloggyBlogPost']['title'].'"</strong> has been exists'
					)
				),
				'content' => array(
					'rule' => 'notEmpty',
					'required' => true,
					'allowEmpty' => false,
					'message' => 'Content field required'
				)
			);
			
			if($this->CloggyValidation->validates()) {	

				$cats = null;
				$tags = null;
				
				if(!empty($this->request->data['CloggyBlogPost']['categories'])) {
					
					if(!is_array($this->request->data['CloggyBlogPost']['categories'])) {
						$exp = explode(',',$this->request->data['CloggyBlogPost']['categories']);
					}else{
						$exp = $this->request->data['CloggyBlogPost']['categories'];
					}
					
					if(!empty($exp)) {																		
						$cats = $this->CloggyBlogCategory->proceedCategories($exp,$this->_user['id']);						
					}
					
				}
				
				if(!empty($this->request->data['CloggyBlogPost']['tags'])) {
						
					$exp = explode(',',$this->request->data['CloggyBlogPost']['tags']);
					if(!empty($exp)) {				
						$tags = $this->CloggyBlogTag->proceedTags($exp,$this->_user['id']);				
					}
						
				}
				
				$postId = 	$this->CloggyBlogPost->generatePost(array(
								'stat' => $this->request->data['submit'] == 'Draft' ? 0 : 1,
								'userId' => $this->_user['id'],
								'title' => $this->request->data['CloggyBlogPost']['title'],
								'content' => $this->request->data['CloggyBlogPost']['content'],
								'cats' => $cats,
								'tags' => $tags
							));								
				
				/*
				 * redirect
				 */
				$this->Session->setFlash('Your post has been added','default',array(),'success');
				$this->redirect(Router::url('/'.Configure::read('Cloggy.url_prefix').'/module/cloggy_blog/cloggy_blog_posts/edit/'.$postId));
				exit();
				
			}else{
				$this->set('errors',$this->CloggyValidation->validationErrors);
			}
			
		}
		
		$this->set('title_for_layout','Cloggy - CloggyBlogPost - Add New Post');
		$this->set(compact('categories','tags'));
		
	}
	
	public function edit($id=null) {				
		
		if(is_null($id)) {
			$this->redirect($this->referer());
			exit();
		}
		
		$this->CloggyBlogPost->cacheQueries = false;
		
		$detail = $this->CloggyBlogPost->getSinglePostById($id);	
		$categories = $this->CloggyBlogCategory->getAllCategories();
		$tags = $this->CloggyBlogTag->getAllTags();				
		$postCategories = $this->CloggyBlogPost->getSinglePostTaxonomies($id);
		$postTags = $this->CloggyBlogPost->getSinglePostTaxonomies($id,'cloggy_blog_tags','cloggy_blog_tag_post');
		
		/*
		 * form submitted
		 */
		if($this->request->is('post')) {
						
			$dataValidate = array();
			$dataToSave = array();		

			$this->request->data['CloggyBlogPost']['content'] = str_replace('\n','',$this->request->data['CloggyBlogPost']['content']);
			
			$title = $this->request->data['CloggyBlogPost']['title'];
			$content = $this->request->data['CloggyBlogPost']['content'];
			
			if($detail['CloggySubject']['subject'] != $title) {
				$dataValidate['title'] = $title;
				$dataToSave['title'] = $title;
			}
			
			if($detail['CloggyContent']['content'] != $content) {
				$dataValidate['content'] = $content;
				$dataToSave['content'] = $content;
			}
			
			if(!empty($dataValidate)) {
				
				if($this->CloggyValidation->validates()) {	
					
					//update main data title & content				
					$this->CloggyBlogPost->updatePost($id,$dataToSave);
										
				}else{
					$this->set('errors',$this->CloggyValidation->validationErrors);
				}
				
			}
			
			/*
			 * proceed taxonomies
			 */
			$cats = null;
			$tags = null;
			
			if(!empty($this->request->data['CloggyBlogPost']['categories'])) {
					
				if(!is_array($this->request->data['CloggyBlogPost']['categories'])) {
					$exp = explode(',',$this->request->data['CloggyBlogPost']['categories']);
				}else{
					$exp = $this->request->data['CloggyBlogPost']['categories'];
				}
					
				if(!empty($exp)) {
					$cats = $this->CloggyBlogCategory->proceedCategories($exp,$this->_user['id']);
				}
					
			}
			
			if(!empty($this->request->data['CloggyBlogPost']['tags'])) {
			
				$exp = explode(',',$this->request->data['CloggyBlogPost']['tags']);
				if(!empty($exp)) {
					$tags = $this->CloggyBlogTag->proceedTags($exp,$this->_user['id']);
				}
			
			}
			
			/*
			 * update taxonomies
			 */
			$this->CloggyBlogPost->updatePostTaxonomies(array(
				'id' => $id,				
				'taxo' => 'cloggy_blog_categories',
				'data' => $cats		
			));
			
			$this->CloggyBlogPost->updatePostTaxonomies(array(
				'id' => $id,				
				'taxo' => 'cloggy_blog_tags',
				'data' => $tags		
			));
			
			/*
			 * update stat
			 */
			$stat = $this->request->data['submit'] == 'Draft' ? 0 : 1;
			$this->CloggyBlogPost->updatePostStat($id,$stat);
			
			/*
			 * redirect
			 */
			$this->Session->setFlash('Your post has been updated','default',array(),'success');
			$this->redirect($this->request->here);
			exit();
			
		}				
		
		$this->set('title_for_layout','Cloggy - CloggyBlogPost - Add New Post');
		$this->set(compact('categories','tags','id','detail','postCategories','postTags'));
		
	}
	
	public function publish($id=null) {
		
		if(is_null($id)) {
			$this->redirect($this->referer());
			exit();
		}
		
		$this->CloggyBlogPost->updatePostStat($id,1);
		$this->redirect($this->referer());
		
	}
	
	public function draft($id=null) {
		
		if(is_null($id)) {
			$this->redirect($this->referer());
			exit();
		}
		
		$this->CloggyBlogPost->updatePostStat($id,0);
		$this->redirect($this->referer());
		
	}
	
	public function remove($id=null) {
		
		if(is_null($id)) {
			$this->redirect($this->referer());
			exit();
		}
		
		$this->CloggyBlogPost->deletePost($id);
		$this->redirect($this->referer());
		
	}
	
}