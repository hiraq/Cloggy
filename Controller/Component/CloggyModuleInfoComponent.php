<?php

App::uses('Component', 'Controller');

class CloggyModuleInfoComponent extends Component {
		
	private $_modules = array();	
	
	public function modules() {
		
		$modules = Configure::read('Cloggy.modules');
		if(!empty($modules) && is_array($modules)) {
			
			foreach($modules as $module) {
				if(!array_key_exists($module,$this->_modules)) {
					
					$this->_configureModuleInfo($module);
					$this->_modules[$module]['name'] = $this->getModuleName();
					$this->_modules[$module]['desc'] = $this->getModuleDesc();
					$this->_modules[$module]['author'] = $this->getModuleAuthor();
					
				}
			}
			
		}
		
	}
	
	public function getModules() {
		return $this->_modules;
	}
	
	public function isModuleExists($module) {
		return array_key_exists($module,$this->_modules);
	}
	
	public function getModuleInfo($module) {
		
		if(array_key_exists($module,$this->_modules)) {			
			return $this->_modules[$module];			
		}else{
			return null;
		}
		
	}
	
	public function getModuleName() {
		$name = Configure::read('basic.name');
		return $name;
	}		
	
	public function getModuleDesc() {
		$desc = Configure::read('basic.desc');
		return $desc;
	}
	
	public function getModuleAuthor() {
		$author = Configure::read('basic.author');
		return $author;
	}
	
	private function _configureModuleInfo($moduleName) {
		
		App::uses('IniReader', 'Configure');
		Configure::config('ini',new IniReader(CLOGGY_PATH_MODULE.$moduleName.DS));
		Configure::load('info.ini','ini');
		
	}
	
}