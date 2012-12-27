<?php

App::uses('ClogAppController','Clog.Controller');
App::uses('Sanitize','Utility');

class ClogBlogPostsController extends ClogAppController {
	
	public $uses = array(		
		'ClogBlogPost',
		'ClogBlogCategory',
		'ClogBlogTag',
		'Clog.ClogValidation'
	);		
	
	private $_user;
	
	public function beforeFilter() {
	
		parent::beforeFilter();
		$this->Auth->deny('*');
	
		$this->ClogModuleMenu->startup($this);
		$this->ClogModuleMenu->add('clog_blog',array(
			'Add New Post' => $this->_base.'/module/clog_blog/clog_blog_posts/add',
			'Manage Posts' => $this->_base.'/module/clog_blog/clog_blog_posts',				
			'Manage Blog' => $this->_base.'/module/clog_blog',
		));
	
		$this->set('moduleKeyMenus','clog_blog');
		
		$this->_user = $this->Auth->user();						
	
	}
	
	public function index() {
		
		$this->paginate = array(
			'ClogBlogPost' => array(
				'limit' => 10,
				'contain' => false,				
				'fields' => array(
					'ClogNode.id',
					'ClogNode.node_created',
					'ClogNode.node_status'
				)
			)
		);
		
		$posts = $this->paginate('ClogBlogPost');		
		$this->set(compact('posts'));
		
	}
	
	public function add() {				
		
		$categories = $this->ClogBlogCategory->getAllCategories();		
		$tags = $this->ClogBlogTag->getAllTags();				
				
		if($this->request->is('post')) {
						
			$dataValidate = $this->request->data['ClogBlogPost'];
			
			/*
			 * custom validation post title
			 */			
			$checkPostSubject = $this->ClogBlogPost->isTitleExists($this->request->data['ClogBlogPost']['title'],$this->_user['id']);
			
			$this->ClogValidation->set($dataValidate);
			$this->ClogValidation->validate = array(
				'title' => array(
					'empty' => array(
						'rule' => 'notEmpty',
						'required' => true,
						'allowEmpty' => false,
						'message' => 'Title field required'
					),
					'exists' => array(
						'rule' => array('isValueEqual',$checkPostSubject,false),
						'message' => 'The title, <strong>"'.$this->request->data['ClogBlogPost']['title'].'"</strong> has been exists'
					)
				),
				'content' => array(
					'rule' => 'notEmpty',
					'required' => true,
					'allowEmpty' => false,
					'message' => 'Content field required'
				)
			);
			
			if($this->ClogValidation->validates()) {	

				$cats = null;
				$tags = null;
				
				if(!empty($this->request->data['ClogBlogPost']['categories'])) {
					
					if(!is_array($this->request->data['ClogBlogPost']['categories'])) {
						$exp = explode(',',$this->request->data['ClogBlogPost']['categories']);
					}else{
						$exp = $this->request->data['ClogBlogPost']['categories'];
					}
					
					if(!empty($exp)) {																		
						$cats = $this->ClogBlogCategory->proceedCategories($exp,$this->_user['id']);						
					}
					
				}
				
				if(!empty($this->request->data['ClogBlogPost']['tags'])) {
						
					$exp = explode(',',$this->request->data['ClogBlogPost']['tags']);
					if(!empty($exp)) {				
						$tags = $this->ClogBlogTag->proceedTags($exp,$this->_user['id']);				
					}
						
				}
				
				$postId = 	$this->ClogBlogPost->generatePost(array(
								'stat' => $this->request->data['submit'] == 'Draft' ? 0 : 1,
								'userId' => $this->_user['id'],
								'title' => $this->request->data['ClogBlogPost']['title'],
								'content' => $this->request->data['ClogBlogPost']['content'],
								'cats' => $cats,
								'tags' => $tags
							));								
				
				/*
				 * redirect
				 */
				$this->Session->setFlash('Your post has been added','default',array(),'success');
				$this->redirect(Router::url('/'.Configure::read('Clog.url_prefix').'/module/clog_blog/clog_blog_posts/edit/'.$postId));
				exit();
				
			}else{
				$this->set('errors',$this->ClogValidation->validationErrors);
			}
			
		}
		
		$this->set('title_for_layout','Clog - ClogBlogPost - Add New Post');
		$this->set(compact('categories','tags'));
		
	}
	
	public function edit($id=null) {				
		
		if(is_null($id)) {
			$this->redirect($this->referer());
			exit();
		}
		
		$this->ClogBlogPost->cacheQueries = false;
		
		$detail = $this->ClogBlogPost->getSinglePostById($id);	
		$categories = $this->ClogBlogCategory->getAllCategories();
		$tags = $this->ClogBlogTag->getAllTags();				
		$postCategories = $this->ClogBlogPost->getSinglePostTaxonomies($id);
		$postTags = $this->ClogBlogPost->getSinglePostTaxonomies($id,'clog_blog_tags','clog_blog_tag_post');
		
		/*
		 * form submitted
		 */
		if($this->request->is('post')) {
						
			$dataValidate = array();
			$dataToSave = array();		

			$this->request->data['ClogBlogPost']['content'] = str_replace('\n','',$this->request->data['ClogBlogPost']['content']);
			
			$title = $this->request->data['ClogBlogPost']['title'];
			$content = $this->request->data['ClogBlogPost']['content'];
			
			if($detail['ClogSubject']['subject'] != $title) {
				$dataValidate['title'] = $title;
				$dataToSave['title'] = $title;
			}
			
			if($detail['ClogContent']['content'] != $content) {
				$dataValidate['content'] = $content;
				$dataToSave['content'] = $content;
			}
			
			if(!empty($dataValidate)) {
				
				if($this->ClogValidation->validates()) {	
					
					//update main data title & content				
					$this->ClogBlogPost->updatePost($id,$dataToSave);
										
				}else{
					$this->set('errors',$this->ClogValidation->validationErrors);
				}
				
			}
			
			/*
			 * proceed taxonomies
			 */
			$cats = null;
			$tags = null;
			
			if(!empty($this->request->data['ClogBlogPost']['categories'])) {
					
				if(!is_array($this->request->data['ClogBlogPost']['categories'])) {
					$exp = explode(',',$this->request->data['ClogBlogPost']['categories']);
				}else{
					$exp = $this->request->data['ClogBlogPost']['categories'];
				}
					
				if(!empty($exp)) {
					$cats = $this->ClogBlogCategory->proceedCategories($exp,$this->_user['id']);
				}
					
			}
			
			if(!empty($this->request->data['ClogBlogPost']['tags'])) {
			
				$exp = explode(',',$this->request->data['ClogBlogPost']['tags']);
				if(!empty($exp)) {
					$tags = $this->ClogBlogTag->proceedTags($exp,$this->_user['id']);
				}
			
			}
			
			/*
			 * update taxonomies
			 */
			$this->ClogBlogPost->updatePostTaxonomies(array(
				'id' => $id,				
				'taxo' => 'clog_blog_categories',
				'data' => $cats		
			));
			
			$this->ClogBlogPost->updatePostTaxonomies(array(
				'id' => $id,				
				'taxo' => 'clog_blog_tags',
				'data' => $tags		
			));
			
			/*
			 * update stat
			 */
			$stat = $this->request->data['submit'] == 'Draft' ? 0 : 1;
			$this->ClogBlogPost->updatePostStat($id,$stat);
			
			/*
			 * redirect
			 */
			$this->Session->setFlash('Your post has been updated','default',array(),'success');
			$this->redirect($this->request->here);
			exit();
			
		}				
		
		$this->set('title_for_layout','Clog - ClogBlogPost - Add New Post');
		$this->set(compact('categories','tags','id','detail','postCategories','postTags'));
		
	}
	
	public function publish($id=null) {
		
		if(is_null($id)) {
			$this->redirect($this->referer());
			exit();
		}
		
		$this->ClogBlogPost->updatePostStat($id,1);
		$this->redirect($this->referer());
		
	}
	
	public function draft($id=null) {
		
		if(is_null($id)) {
			$this->redirect($this->referer());
			exit();
		}
		
		$this->ClogBlogPost->updatePostStat($id,0);
		$this->redirect($this->referer());
		
	}
	
	public function remove($id=null) {
		
		if(is_null($id)) {
			$this->redirect($this->referer());
			exit();
		}
		
		$this->ClogBlogPost->deletePost($id);
		$this->redirect($this->referer());
		
	}
	
}