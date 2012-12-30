<?php

App::uses('AppHelper', 'View/Helper');

/**
 * 
 * Configure and load cloggy menus
 * for views
 * 
 * @author hiraq
 * @name CloggyMenusHelper
 * @package Cloggy
 * @subpackage Helper
 */
class CloggyMenusHelper extends AppHelper {
	
	/**
	 * 
	 * Save View variables
	 * 
	 * @access private
	 * @var array
	 */
	private $_vars;
	
	/**
	 * Used helpers
	 * 
	 * @access public
	 * @var array
	 */
	public $helpers = array('Html');		
	
	/**
	 * Get View variables
	 * 
	 * @access public
	 * @return array
	 */
	public function getViewVars() {				
		$this->_vars = $this->_View->getVars();		
		return $this->_vars;		
	}

	/**
	 * Get View variable value
	 * 
	 * @access public
	 * @param string $key
	 * @return string|array
	 */
	public function getViewVarValue($key) {
		return $this->_View->getVar($key);	
	}
	
	/**
	 * 
	 * Create html link
	 * 
	 * @access public
	 * @param string $key
	 * @param string $url
	 * @return string
	 */
	public function getLink($key,$url) {		
		return $this->Html->link(ucfirst(strtolower($key)),Router::url($url,true));
	}
	
	/**
	 * Get http url by Router configuration
	 * 
	 * @access public
	 * @param string $url
	 * @return string
	 */
	public function getUrl($url) {
		return Router::url($url,true);
	}	
	
	/**
	 * 
	 * Get menus for views
	 * 
	 * @access public
	 * @param string $key
	 * @param string $group [optional]
	 * @return null|array|boolean
	 */
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