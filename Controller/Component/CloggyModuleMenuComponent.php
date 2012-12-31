<?php
App::uses('Component', 'Controller');

/**
 * 
 * Cloggy Module Menu Component, setup cloggy menus
 * from Controller using component
 * 
 * @author hiraq
 * @name CloggyModuleMenuComponent
 * @package Cloggy
 * @subpackage Component
 */
class CloggyModuleMenuComponent extends Component {
	
/**
 * 
 * Setup requested Controller
 * 
 * @access private
 * @var object
 */
	private $__Controller;
	
/**
 * Store all menus
 * 
 * @access private
 * @var array
 */
	private $__menus = array();
	
/**
 * 
 * Grouping menus
 * 
 * @access private
 * @var array
 */
	private $__groups = array();
	
/**
 * CakePHP Component Callback
 * 
 * @access public
 * @see Component::startup()
 */
	public function startup(Controller $controller) {
		parent::startup($controller);
		$this->__Controller = $controller;
	}
	
/**
 * Get Controller viewVars variable
 * 
 * @access public
 * @return array
 */
	public function getViewVars() {
		return $this->__Controller->viewVars;
	}
	
/**
 * Get requested controller name
 * 
 * @access public
 * @return string
 */
	public function getRequestedControllerName() {
		return $this->__Controller->name;
	}
	
/**
 * Get menus
 * @return array
 */
	public function getMenus() {
		return $this->__menus;
	}
	
/**
 * Create and merging menus viewVars
 * 
 * @access public
 * @param string $key
 * @param array $menus
 * @return void
 */
	public function menus($key,$menus) {		
		/*
		 * create new menus for requested controller
		 */
		$controllerName = $this->getRequestedControllerName();
		$this->__create($controllerName, $key, $menus);
		
		/*
		 * merging or create new viewVars for cloggy_menus
		 */
		$this->__mergingControllerViewVars($controllerName);
	}
	
/**
 * Add new menus
 * 
 * @access public
 * @param string $key
 * @param array $newMenus
 * @return void
 */
	public function add($key,$newMenus) {
		$controllerName = $this->getRequestedControllerName();
		$controllerVars = $this->getViewVars();
		
		if (!array_key_exists($controllerName,$this->__menus)) {
			$this->menus($key, $newMenus);
		} else {
			
			$menus = $this->__menus[$controllerName];			
			if (array_key_exists($key,$menus) && is_array($menus[$key])) {			
				$this->__menus[$controllerName][$key] = array_merge($menus[$key],$newMenus);												
			} else {
				$this->__menus[$controllerName][$key] = $newMenus;	
			}
			
			/*
			 * reset
			*/
			if (array_key_exists('cloggy_menus',$controllerVars)
					&& array_key_exists($key,$controllerVars['cloggy_menus'])) {
				unset($this->__Controller->viewVars['cloggy_menus'][$key]);
			}
			$this->__Controller->viewVars['cloggy_menus'][$key] = $this->__menus[$controllerName][$key];			
		}
	}
	
/**
 * Remove key menus from requested controller
 * 
 * @access public
 * @param string $key
 */
	public function remove($key) {		
		$controllerName = $this->getRequestedControllerName();
		$controllerVars = $this->getViewVars();
		
		if (array_key_exists($controllerName,$this->__menus)
				&& array_key_exists($key,$this->__menus[$controllerName])) {						
			unset($this->__menus[$controllerName][$key]);
		}
		
		if (array_key_exists('cloggy_menus',$controllerVars)
				&& array_key_exists($key,$controllerVars['cloggy_menus'])) {
			unset($this->__Controller->viewVars['cloggy_menus'][$key]);
		}
	}
	
/**
 * 
 * Grouping key menus
 * 
 * @access public
 * @param string $groupName
 * @param array $keys
 */
	public function setGroup($groupName,$menus) {		
		
		$controllerName = $this->getRequestedControllerName();
		$controllerVars = $this->getViewVars();
				
		$this->__groups[$controllerName][$groupName] = $menus;
		
		if (array_key_exists('cloggy_menus_group',$controllerVars)) {
			
			if (array_key_exists($groupName,$controllerVars['cloggy_menus_group'])) {
				$this->__Controller->viewVars['cloggy_menus_group'][$groupName] = array_merge(
						$this->__Controller->viewVars['cloggy_menus_group'][$groupName], $this->__groups[$controllerName][$groupName]);
			} else {								
				$this->__Controller->viewVars['cloggy_menus_group'][$groupName] = $menus;
			}
			
		} else {
			$this->__Controller->viewVars['cloggy_menus_group'][$groupName] = $menus;
		}
		
	}
	
/**
 * Get group menus
 * 
 * @access public
 * @param string $groupName
 * @return null|array
 */
	public function getGroup($groupName) {
		$controllerName = $this->getRequestedControllerName();
		if (isset($this->__groups[$controllerName])
				&& array_key_exists($groupName,$this->__groups[$controllerName])) {
			return $this->__groups[$controllerName][$groupName];
		} else {
			return null;
		}
	}
	
/**
 * 
 * Create url for module
 * 
 * @access public
 * @param string $moduleName
 * @param string $path
 * @return string
 */	
	public function urlModule($moduleName,$path) {
		$base = '/'.Configure::read('Cloggy.url_prefix').'/module/'.$moduleName.'/';
		return $base.$path;
	}
	
/**
 * Genereate url inside Cloggy admin
 * 
 * @access public
 * @param string $path
 * @return string
 */	
	public function url($path) {
		$base = '/'.Configure::read('Cloggy.url_prefix').'/';
		return $base.$path;
	}
	
/**
 * Manipulate Controller viewVars
 * 
 * @access private
 * @param string $controllerName
 */
	private function __mergingControllerViewVars($controllerName) {
		$controllerVars = $this->getViewVars();
		
		/*
		 * merge with Controller view vars inside cloggy_menus
		 */
		if (!empty($controllerVars)
				&& array_key_exists('cloggy_menus',$controllerVars)) {
			
			$this->__Controller->viewVars['cloggy_menus'] = array_merge(
					$this->__Controller->viewVars['cloggy_menus'],$this->__menus[$controllerName]);
			
		} else {
			$this->__Controller->set('cloggy_menus',$this->__menus[$controllerName]);
		}
	}
	
/**
 * Register into $__menus
 * 
 * @access private
 * @param string $name
 * @param string $key
 * @param array $menus
 * @return void
 */
	private function __create($name,$key,$menus) {
		if (!array_key_exists($name,$this->__menus)) {
			$this->__menus[$name] = array($key => $menus);
		}
	}
}