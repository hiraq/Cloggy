<?php

class ClogAppController extends AppController {	
		
	public $helpers = array(
		'Html',
		'Clog.ClogAsset',
		'Clog.ClogMenus',
		'Session',
		'Paginator' => array(
			'className' => 'Clog.ClogPaginator'
		)
	);	
	public $components = array(
		'Session',
		'Auth',
		'Clog.ClogModuleInfo',
		'Clog.ClogModuleMenu',
		'Paginator'
	);
	
	public $layout = 'Clog.clog_layout';
		
	protected $_base;
	protected $_requestedModule;
	
	public function beforeFilter() {
		
		parent::beforeFilter();		

		//base url for plugin
		$this->_base = '/'.Configure::read('Clog.url_prefix');			
		
		//load auth
		$this->_authSettings();
		
		/*
		 * set default clog menus
		 */
		$this->set('clog_menus',array(
			'clog' => array(
				'dashboard' => '/'.Configure::read('Clog.url_prefix').'/dashboard',				
				'logout' => '/'.Configure::read('Clog.url_prefix').'/logout',
			)
		));
		
		//generate modules
		$this->ClogModuleInfo->modules();
		$modules = $this->ClogModuleInfo->getModules();				
		
		//set global variable
		$this->set(compact('modules'));

		/*
		 * check if requested params is module request
		 * > change layout
		 */
		if(isset($this->request->params['isClogModule']) 
				&& $this->request->params['isClogModule'] == 1) {
			$this->layout = 'clog_module_layout';
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
				'userModel' => 'ClogUser',
				'scope' => array('ClogUser.user_status' => 1),
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
			'plugin' => 'clog'
		);
		$this->Auth->loginRedirect = array(
			'controller' => 'dashboard',
			'action' => 'index',
			'plugin' => 'clog'
		);
		$this->Auth->logoutRedirect = array(
			'controller' => 'home',
			'action' => 'login',
			'plugin' => 'clog'
		);
		
	}
		
}