<?php

App::uses('Sanitize', 'Utility');

class InstallController extends ClogAppController {
	
	public $uses = array('Clog.ClogUser','Clog.ClogValidation');
	public $helpers = array('Form');
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('*');
	}
	
	public function index() {
	
		$users =  $this->ClogUser->find('count',array(
			'contain' => false
		));
		
		if($users < 1) {
			
			/*
			 * form submitted & processing data
			 */
			if($this->request->is('post')) {								
				
				/*
				 * sanitize user input
				 */
				$this->request->data = Sanitize::clean($this->request->data,array(
					'encode' => true,
					'remove_html' => true
				));				
				
				$dataValidate = $this->request->data['ClogUser'];
				
				/*
				 * validation rules
				 */
				$this->ClogValidation->set($dataValidate);
				$this->ClogValidation->validate = array(
					'user_name' => array(
						'rule' => 'notEmpty',
						'required' => true,
						'allowEmpty' => false,
						'message' => 'Username field cannot be empty'
					),
					'user_password' => array(
						'rule' => 'notEmpty',
						'required' => true,
						'allowEmpty' => false,
						'message' => 'Password field cannot be empty'
					),
					'user_email' => array(
						'rule' => 'email',
						'required' => true,
						'allowEmpty' => false,
						'message' => 'Valid address required'
					)
				);
				
				if($this->ClogValidation->validates()) {
					
					/*
					 * setup user data
					 */
					$this->request->data['ClogUser']['user_password'] = AuthComponent::password($this->request->data['ClogUser']['user_password']);					
					$this->request->data['ClogUser'] = array_merge($this->request->data['ClogUser'],array(
						'user_last_login' => date('c'),
						'user_role' => 'super administrator',
						'user_created' => date('c'),
						'user_status' => 1
					));
					
					/*
					 * save new user
					 */
					$this->ClogUser->create();
					$this->ClogUser->save($this->request->data);
					
					/*
					 * install success
					 */
					$this->Session->setFlash('Your account has been activated.','default',array(),'install_success');
					$this->redirect($this->_base.'/login');
					
				}else{
					$this->set('errors',$this->ClogValidation->validationErrors);					
				}
				
			}//end processing form
			
			//page title
			$this->set('title_for_layout','Clog - Setup User');
			
		}
		
	}
	
}