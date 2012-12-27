<?php

class HomeController extends ClogAppController {	

	public $uses = array('Clog.ClogUser','Clog.ClogValidation','Clog.ClogUserLogin');
	public $helpers = array('Form');
	private $_userCount;
	private $_userId;
	
	public function beforeFilter() {
		
		parent::beforeFilter();
		
		//allow all
		$this->Auth->allow();		
		
		/*
		 * check if has users
		*/
		$this->_userCount = $this->ClogUser->find('count',array('contain' => false));
		$this->_userId = $this->Auth->user('id');			
				
	}		
	
	public function index() {		

		/*
		 * check if user has logged in
		 */
		if($this->Auth->loggedIn()) {
			$this->redirect($this->Auth->loginRedirect);
		}
		
		$this->_redirectIfNoUsers();	
		$this->redirect(array('action' => 'login'));
			
	}				

	public function login() {				
		
		//redirect if user empty
		$this->_redirectIfNoUsers();
		
		/*
		 * form submitted
		 */
		if($this->request->is('post')) {
			
			if($this->Auth->login()) {								
				
				$this->_userId = $this->Auth->user('id');
				$this->ClogUser->setUserLastLogin($this->_userId);
				$this->ClogUserLogin->setLogin($this->_userId);
				$this->redirect($this->Auth->loginRedirect);
				
			}else{
				$this->Auth->flash('Wrong username or password');
				$this->redirect($this->Auth->loginAction);
			}
			
		}
		
		$this->set('title_for_layout','Clog - Administrator Login');
		
	}
	
	public function logout() {
		
		$this->ClogUser->setUserLastLogin($this->_userId);
		$this->ClogUserLogin->removeLogin($this->_userId);
		$this->redirect($this->Auth->logout());
		
	}
	
	private function _redirectIfNoUsers() {
		
		/*
		* if there are no users
		* then install (setup first users)
		*/
		if($this->_userCount < 1) {
			$this->redirect($this->_base.'/install');			
		}
		
	}
	
}