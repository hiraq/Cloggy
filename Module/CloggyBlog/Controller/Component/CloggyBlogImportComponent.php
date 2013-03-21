<?php

App::uses('Component', 'Controller');

class CloggyBlogImportComponent extends Component {
    
    private $__data;
    private $__adapter = 'WordPress';
    private $__adapterClass;
    private $__options = array();
    
    /**
     * Register path
     */
    public function buildPath() {
        
        App::build(array(
            'Controller/Component/CloggyBlogImport' => array(CLOGGY_PATH_MODULE.'CloggyBlog'.DS.'Controller'.DS.'Component'.DS.'CloggyBlogImport'.DS)
        ),APP::REGISTER);
        
    }
    
    /**
     * Setup importer adapter class
     * @param string $adapter
     */
    public function setAdapter($adapter) {
        $this->__adapter = $adapter;
    }
    
    /**
     * Data to import
     * @param array $data
     */
    public function setupData($data) {
        $this->__data = $data;
    }
    
    /**
     * Setup options
     * @param array $options
     */
    public function setupOptions($options) {
        $this->__options = array_merge($this->__options,$options);
    }
    
    /**
     * Generate adapter object
     */
    public function generate() {
        
        $adapterClassName = 'Import'.$this->__adapter;
        App::uses($adapterClassName, 'Controller/Component/CloggyBlogImport');
        
        $this->__adapterClass = new $adapterClassName($this->__data,$this->__options);
        
    }
    
    /**
     * Check if given data is valid or not
     * @return boolean
     */
    public function isValidImportedData() {
        return $this->__adapterClass->isValid();
    }
    
    public function import() {
        return $this->__adapterClass->import();
    }
    
}