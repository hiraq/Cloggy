<?php

App::uses('AppHelper', 'View/Helper');

class CloggyMenusHelper extends AppHelper {
	
	private $_vars;
	public $helpers = array('Html');		
	
	public function getViewVars() {				
		$this->_vars = $this->_View->getVars();		
		return $this->_vars;		
	}

	public function getViewVarValue($key) {
		return $this->_View->getVar($key);	
	}
	
	public function getLink($key,$url) {		
		return $this->Html->link(ucfirst(strtolower($key)),Router::url($url,true));
	}
	
	public function getUrl($url) {
		return Router::url($url,true);
	}	
	
	public function menu($key,$group='cloggy_menus') {
		$menus = $this->getViewVarValue($group);
		if($menus) {
			
			if(array_key_exists($key,$menus)) {
				if(!empty($menus[$key])) {
					return $menus[$key];
				}
			}						
			
		}
		
		return false;
	}
	
}