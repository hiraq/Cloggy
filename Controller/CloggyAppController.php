<?php

class CloggyAppController extends AppController {	
		
	public $helpers = array(
		'Html',
		'Cloggy.CloggyAsset',
		'Cloggy.CloggyMenus',
		'Session',
		'Paginator' => array(
			'className' => 'Cloggy.CloggyPaginator'
		)
	);	
	public $components = array(
		'Session',
		'Auth',
		'Cloggy.CloggyModuleInfo',
		'Cloggy.CloggyModuleMenu',
		'Paginator'
	);
	
	public $layout = 'Cloggy.cloggy_layout';
		
	protected $_base;
	protected $_requestedModule;
	
	public function beforeFilter() {
		
		parent::beforeFilter();		

		//base url for plugin
		$this->_base = '/'.Configure::read('Cloggy.url_prefix');			
		
		//load auth
		$this->_authSettings();
		
		/*
		 * set default cloggy menus
		 */
		$this->set('cloggy_menus',array(
			'cloggy' => array(
				'dashboard' => '/'.Configure::read('Cloggy.url_prefix').'/dashboard',				
				'logout' => '/'.Configure::read('Cloggy.url_prefix').'/logout',
			)
		));
		
		//generate modules
		$this->CloggyModuleInfo->modules();
		$modules = $this->CloggyModuleInfo->getModules();				
		
		//set global variable
		$this->set(compact('modules'));

		/*
		 * check if requested params is module request
		 * > change layout
		 */
		if(isset($this->request->params['isCloggyModule']) 
				&& $this->request->params['isCloggyModule'] == 1) {
			$this->layout = 'cloggy_module_layout';
			$this->_requestedModule = $this->request->params['name'];
			$this->set('moduleName',$this->request->params['name']);			
		}
		
	}				
	
	public function beforeRender() {
		$this->response->disableCache();
	}
	
	private function _authSettings() {
		
		$this->Auth->authenticate = array(
			'Form' => array(
				'userModel' => 'CloggyUser',
				'scope' => array('CloggyUser.user_status' => 1),
				'fields' => array(
					'username' => 'user_name',
					'password' => 'user_password'
				)
			)
		);
		
		$this->Auth->authError = 'Identify yourself!';
		$this->Auth->loginAction = array(
			'controller' => 'home',
			'action' => 'login',
			'plugin' => 'cloggy'
		);
		$this->Auth->loginRedirect = array(
			'controller' => 'dashboard',
			'action' => 'index',
			'plugin' => 'cloggy'
		);
		$this->Auth->logoutRedirect = array(
			'controller' => 'home',
			'action' => 'login',
			'plugin' => 'cloggy'
		);
		
	}
		
}