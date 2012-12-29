<?php

App::uses('Component', 'Controller');

class CloggyModuleMenuComponent extends Component {
	
	private $_Controller;
	private $_menus = array();
	
	/**
	 * CakePHP Component Callback
	 * @see Component::startup()
	 */
	public function startup(Controller $controller) {
		parent::startup($controller);
		$this->_Controller = $controller;		
	}
	
	/**
	 * Get Controller viewVars variable
	 * @return array
	 */
	public function getViewVars() {
		return $this->_Controller->viewVars;
	}		
	
	/**
	 * Get requested controller name
	 * @return string
	 */
	public function getRequestedControllerName() {
		return $this->_Controller->name;
	}
	
	/**
	 * Get menus
	 * @return array
	 */
	public function getMenus() {
		return $this->_menus;
	}
	
	/**
	 * Create and merging menus viewVars
	 * 
	 * @param string $key
	 * @param array $menus
	 * @return void
	 */
	public function menus($key,$menus) {
		
		/*
		 * create new menus for requested controller
		 */
		$controllerName = $this->getRequestedControllerName();
		$this->_create($controllerName, $key, $menus);
		
		/*
		 * merging or create new viewVars for cloggy_menus
		 */
		$this->_mergingControllerViewVars($controllerName);
		
	}
	
	/**
	 * Add new menus
	 * @param string $key
	 * @param array $newMenus
	 * @return void
	 */
	public function add($key,$newMenus) {
		
		$controllerName = $this->getRequestedControllerName();
		$controllerVars = $this->getViewVars();
		
		if(!array_key_exists($controllerName,$this->_menus)) {
			$this->menus($key, $newMenus);
		}else{
			
			$menus = $this->_menus[$controllerName];
			
			if(array_key_exists($key,$menus) && is_array($menus[$key])) {								
				$this->_menus[$controllerName][$key] = array_merge($menus[$key],$newMenus);												
			}else{				
				$this->_menus[$controllerName][$key] = $newMenus;				
			}
			
			/*
			 * reset
			*/
			if(array_key_exists('cloggy_menus',$controllerVars)
					&& array_key_exists($key,$controllerVars['cloggy_menus'])) {
				unset($this->_Controller->viewVars['cloggy_menus'][$key]);
			}
			
			$this->_Controller->viewVars['cloggy_menus'][$key] = $this->_menus[$controllerName][$key];
			
		}
		
	}
	
	/**
	 * Remove key menus from requested controller
	 * @param string $key
	 */
	public function remove($key) {
		
		$controllerName = $this->getRequestedControllerName();
		$controllerVars = $this->getViewVars();
		
		if(array_key_exists($controllerName,$this->_menus) && array_key_exists($key,$this->_menus[$controllerName])) {						
			unset($this->_menus[$controllerName][$key]);
		}
		
		if(array_key_exists('cloggy_menus',$controllerVars) && array_key_exists($key,$controllerVars['cloggy_menus'])) {
			unset($this->_Controller->viewVars['cloggy_menus'][$key]);
		}
		
	}
	
	/**
	 * Manipulate Controller viewVars
	 * @param string $controllerName
	 */
	private function _mergingControllerViewVars($controllerName) {
		
		$controllerVars = $this->getViewVars();
		
		/*
		 * merge with Controller view vars inside cloggy_menus
		 */
		if(!empty($controllerVars) && array_key_exists('cloggy_menus',$controllerVars)) {
			
			$this->_Controller->viewVars['cloggy_menus'] = array_merge(
					$this->_Controller->viewVars['cloggy_menus'],$this->_menus[$controllerName]);
			
		}else{
			$this->_Controller->set('cloggy_menus',$this->_menus[$controllerName]);
		}
		
	}
	
	/**
	 * Register into $_menus
	 * 
	 * @param string $name
	 * @param string $key
	 * @param array $menus
	 * @return void
	 */
	private function _create($name,$key,$menus) {
		if(!array_key_exists($name,$this->_menus)) {
			$this->_menus[$name] = array($key => $menus);
		}
	}
	
}