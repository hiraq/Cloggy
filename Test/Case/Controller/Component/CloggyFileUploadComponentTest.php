<?php

App::uses('Controller', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('ComponentCollection', 'Controller');
App::uses('CloggyFileUploadComponent', 'Cloggy.Controller/Component');

class CloggyFileUploadComponentTest extends CakeTestCase {
    
    private $__CloggyFileUpload;
    private $__Controller;
    private $__Collection;
    private $__Request;
    private $__Response;

    public function setUp() {

        parent::setUp();

        // Setup our component and fake test controller
        $this->__Collection = new ComponentCollection();        
        $this->__Request = new CakeRequest();
        $this->__Response = new CakeResponse();                
                
    }
    
    public function testObject() {   
        $this->__createComponentObject();
        $this->assertTrue(is_a($this->__CloggyFileUpload,'CloggyFileUploadComponent'));        
    }
    
    public function testSettings() {
        
        $this->__createComponentObject(array(
            'folder_dest_path' => 'test_path',
            'allowed_types' => array('jpg','jpeg','png'),
            'max_file_size' => 300000,
            'force_dup_filename' => true,
            'auto_upload' => true,
            'field' => 'test_field',
            'callback' => 'test_callback'
        ));
        
        $maxfilesize = $this->__CloggyFileUpload->getMaxFileSize();
        $folderpath = $this->__CloggyFileUpload->getFolderDest();
        $allowedTypes = $this->__CloggyFileUpload->getAllowedFileTypes();
        $forcedup = $this->__CloggyFileUpload->isForceDupFile();
        $autoUpload = $this->__CloggyFileUpload->isAutoUpload();
        $field = $this->__CloggyFileUpload->getFieldName();
        $callback = $this->__CloggyFileUpload->getCallback();
        $isCallbackExists = $this->__CloggyFileUpload->isCallbackExists();
        
        $this->assertEqual($maxfilesize,300000);
        $this->assertEqual($folderpath,'test_path');
        $this->assertEqual($allowedTypes,array('jpg','jpeg','png'));
        $this->assertEqual($field,'test_field');
        $this->assertEqual($callback,'test_callback');      
        
        $this->assertTrue($forcedup);
        $this->assertTrue($autoUpload);
        $this->assertFalse($isCallbackExists);
        
    }
    
    private function __createComponentObject($settings=array()) {
                
        if (empty($this->__Controller)) {
            $this->__Controller = new Controller($this->__Request, $this->__Response);
        }
        
        $this->__Collection->init($this->__Controller);        
        $this->__CloggyFileUpload = new CloggyFileUploadComponent($this->__Collection,$settings);
        
    }
    
}