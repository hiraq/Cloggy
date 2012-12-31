<?php

App::uses('AppHelper', 'View/Helper');

/**
 * 
 * Manage assets
 * 
 * @author hiraq
 * @name CloggyAssetHelper
 * @package Cloggy
 * @subpackage Component
 */
class CloggyAssetHelper extends AppHelper {
	
/**
 * Helper dependencies
 * 
 * @access public
 * @var array
 */
	public $helpers = array('Html');			
	
/**
 * Get webroot/<theme_used>/vendors url
 * 
 * @access public
 * @param string $file
 * @return string
 */	
	public function getVendorUrl($file) {
		
		$base = Router::url('/',true).$this->__getBaseUrl();
		$url = $base.'/vendors/';
		return $url.$file;	
		
	}

/**
 * Generate html tag by type:
 * > css : <link>
 * > js : <script>
 * 
 * Get file from webroot/<theme_used>/app
 * 
 * @access public
 * @param string $file
 * @param string $type
 * @return boolean|string
 */	
	public function getVendorHtmlTag($file,$type) {
		
		$base = '/'.$this->__getBaseUrl();
		$url = $base.'/vendors/';
		
		switch($type) {
			
			case 'js':
				return $this->Html->script($url.$file);
				break;
				
			case 'css':
				return $this->Html->css($url.$file);
				break;
			
		}
		
		return false;
		
	}

/**
 * Generate js url from webroot/<theme_used>/app/js
 * 
 * @access public
 * @param string $file
 * @return string
 */	
	public function getJsUrl($file) {
		
		$base = Router::url('/',true).$this->__getBaseUrl();
		$url = $base.'/app/js/';
		return $url.$file.'.js';
		
	}		
	
/**
 * Generate html js tag <script> from webroot/<theme_used>/app/js
 * 
 * @access public
 * @param string $file
 * @return string
 */	
	public function getJsHtmlTag($file) {
		
		$base = '/'.$this->__getBaseUrl();
		$url = $base.'/app/js/';
		return $this->Html->script($url.$file);
		
	}		
	
/**
 * 
 * Generate css url from webroot/<theme_used>/app/css
 * 
 * @access public
 * @param string $file
 * @return string
 */	
	public function getCssUrl($file) {
		
		$base = Router::url('/',true).$this->__getBaseUrl();
		$url = $base.'/app/css/';
		return $url.$file.'.css';
		
	}
	
/**
 * 
 * Generate html <link> for css from webroot/<theme_used>/app/css
 * 
 * @access public
 * @param string $file
 * @return string
 */	
	public function getCssHtmlTag($file) {
		
		$base = '/'.$this->__getBaseUrl();
		$url = $base.'/app/css/';
		return $this->Html->css($url.$file);
		
	}
	
/**
 * Get base url based by used theme and url prefix
 * 
 * @access private
 * @return string
 */	
	private function __getBaseUrl() {			
		return Configure::read('Cloggy.url_prefix').'/'.Configure::read('Cloggy.theme_used');
	}
	
}