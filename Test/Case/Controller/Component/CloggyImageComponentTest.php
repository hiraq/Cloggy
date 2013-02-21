<?php

App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('Controller', 'Controller');
App::uses('AppController', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('ComponentCollection', 'Controller');
App::uses('CloggyImageComponent', 'Cloggy.Controller/Component');

class CloggyImageComponentTest extends CakeTestCase {
    
    private $__CloggyImage;
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
        
        //clear
        clearstatcache();
                
    }
    
    public function testObject() {   
        $this->__createComponentObject();
        $this->assertTrue(is_a($this->__CloggyImage,'CloggyImageComponent'));        
    }
    
    public function testSettings() {
        
        $this->__createComponentObject(array(
            'image' => $this->__getTestImage(),
            'width' => 100,
            'height' => 100,
            'option' => 'crop',
            'save_path' => $this->__getTestImageSavePath()
        ));
        
        $isExtensionLoaded = $this->__CloggyImage->isExtensionLoaded();
        $checkError = $this->__CloggyImage->isError();
        $errorMsg = $this->__CloggyImage->getErrorMsg();
        
        $this->assertFalse($checkError);
        $this->assertTrue(empty($errorMsg));
        $this->assertTrue($isExtensionLoaded);
        
        $imageExt = $this->__CloggyImage->getImageExt();
        $this->assertEqual($imageExt,'jpeg');        
        
        $imagePath = $this->__CloggyImage->getImagePath();
        $imageSavePath = $this->__CloggyImage->getSavePath();
        $option = $this->__CloggyImage->getOption();
        $command = $this->__CloggyImage->getCommand();
        
        $this->assertEqual($imagePath,$this->__getTestImage());
        $this->assertEqual($imageSavePath,$this->__getTestImageSavePath());
        $this->assertEqual($option,'crop');
        $this->assertEqual($command,'crop');                
        
        $this->__createComponentObject(array(
            'option' => 'crop'            
        ));
        
        $this->__CloggyImage->settings(array(
            'option' => 'auto',
            'command' => 'resize'
        ));
                
        $option2 = $this->__CloggyImage->getOption();
        $command2 = $this->__CloggyImage->getCommand();
                
        $this->assertEqual($option2,'auto');
        $this->assertEqual($command2,'resize');
        
    }        
    
    private function __getTestImageSavePath($filename=null) {
        
        $dir = APP.'plugin'.DS.'Cloggy'.DS.'webroot'.DS;
        if (is_null($filename)) {
            $filename = 'PHP-icon.jpeg';
        }
        
        return $dir.$filename;
        
    }
        
    
    private function __getTestImage() {
        return APP.'Plugin'.DS.'Cloggy'.DS.'webroot'.DS.'test'.DS.'PHP-icon.jpeg';
    }
    
    private function __createComponentObject($settings=array()) {
                
        if (empty($this->__Controller)) {
            $this->__Controller = new Controller($this->__Request, $this->__Response);
        }
        
        $this->__Collection->init($this->__Controller);        
        $this->__CloggyImage = new CloggyImageComponent($this->__Collection,$settings);
        
    }
    
}