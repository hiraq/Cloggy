<?php

App::uses('Component', 'Controller');

/**
 * Manage modules including their info.ini
 * 
 * @author hiraq
 * @name CloggyModuleInfoComponent
 * @package Cloggy
 * @subpackage Component
 */
class CloggyModuleInfoComponent extends Component {
		
/**
 * Store registered modules
 * 
 * @access private
 * @var array
 */	
	private $__modules = array();	
	
/**
 * Setup and get all registered modules
 * @access public
 */	
	public function modules() {		
		$modules = Configure::read('Cloggy.modules');
		if(!empty($modules) && is_array($modules)) {			
			foreach($modules as $module) {
				if (!array_key_exists($module,$this->__modules)) {					
					$this->_configureModuleInfo($module);
					$this->__modules[$module]['name'] = $this->getModuleName();
					$this->__modules[$module]['desc'] = $this->getModuleDesc();
					$this->__modules[$module]['author'] = $this->getModuleAuthor();					
				}
			}			
		}		
	}
	
/**
 * Get all modules
 * @access public
 */	
	public function getModules() {
		return $this->__modules;
	}
	
/**
 * Check if module exists or not
 * @access public
 * @param string $module
 * @return boolean
 */	
	public function isModuleExists($module) {
		return array_key_exists($module,$this->__modules);
	}
	
/**
 * Get module info
 * @access public
 * @param string $module
 * @return null|array
 */	
	public function getModuleInfo($module) {		
		if (array_key_exists($module,$this->__modules)) {			
			return $this->__modules[$module];			
		} else {
			return null;
		}		
	}
	
/**
 * Get module name
 * @access public
 * @return string
 */	
	public function getModuleName() {
		$name = Configure::read('basic.name');
		return $name;
	}		
	
/**
 * Get module description
 * @access public
 * @return string
 */	
	public function getModuleDesc() {
		$desc = Configure::read('basic.desc');
		return $desc;
	}
	
/**
 * Get module author
 * @access public
 * @return string
 */	
	public function getModuleAuthor() {
		$author = Configure::read('basic.author');
		return $author;
	}
	
/**
 * Load info.ini for requested module
 * @access private
 * @param string $moduleName
 */	
	private function _configureModuleInfo($moduleName) {		
		App::uses('IniReader', 'Configure');
		Configure::config('ini',new IniReader(CLOGGY_PATH_MODULE.$moduleName.DS));
		Configure::load('info.ini','ini');		
	}
	
}