<?php

App::uses('AppHelper', 'View/Helper');

class CloggyBlogAssetHelper extends AppHelper {
    
    /**
     * Helper dependencies
     * 
     * @access public
     * @var array
     */
    public $helpers = array('Html');
    
    /**
     *
     * CloggyBlog image path
     * 
     * @access private
     * @var string 
     */
    private $__imagePath = '/uploads/CloggyBlog/images/';
    
    /**
     * Get image url path
     * @param string $filename
     */
    public function getImageUrl($postId,$filename) {
        
        $filepath = $this->__dirPath().$filename;
        if (file_exists($filepath)) {            
            return $this->__baseUrl().$this->__imagePath.$postId.'/'.$filename;            
        }
        
    }
    
    /**
     * Get image file path
     * @param string $filepath
     * @return string
     */
    public function getImage($filepath) {
        return $this->__baseUrl().$filepath;
    }
    
    /**
     * Get base image upload url
     * @return string
     */
    public function getImageUploadPath() {
        return $this->__baseUrl().$this->__imagePath;
    }
    
    /**
     * Get base url
     * @return string
     */
    private function __baseUrl() {
        return '/'.Configure::read('Cloggy.url_prefix');
    }
    
    /**
     * Get base dir path
     * @return string
     */
    private function __dirPath() {
        return APP.'Plugin'.DS.'Cloggy'.DS.'webroot'.DS;
    }
    
}