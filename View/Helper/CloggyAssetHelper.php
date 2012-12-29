<?php

App::uses('AppHelper', 'View/Helper');

class CloggyAssetHelper extends AppHelper {
	
	public $helpers = array('Html');			
	
	public function getVendorUrl($file) {
		
		$base = Router::url('/',true).$this->_getBaseUrl();
		$url = $base.'/vendors/';
		return $url.$file;	
		
	}

	public function getVendorHtmlTag($file,$type) {
		
		$base = '/'.$this->_getBaseUrl();
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
		
	public function getJsUrl($file) {
		
		$base = Router::url('/',true).$this->_getBaseUrl();
		$url = $base.'/app/js/';
		return $url.$file.'.js';
		
	}		
	
	public function getJsHtmlTag($file) {
		
		$base = '/'.$this->_getBaseUrl();
		$url = $base.'/app/js/';
		return $this->Html->script($url.$file);
		
	}		
	
	public function getCssUrl($file) {
		
		$base = Router::url('/',true).$this->_getBaseUrl();
		$url = $base.'/app/css/';
		return $url.$file.'.css';
		
	}
	
	public function getCssHtmlTag($file) {
		
		$base = '/'.$this->_getBaseUrl();
		$url = $base.'/app/css/';
		return $this->Html->css($url.$file);
		
	}
	
	private function _getBaseUrl() {			
		return Configure::read('Cloggy.url_prefix').'/'.Configure::read('Cloggy.theme_used');
	}
	
}