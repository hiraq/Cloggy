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
    private $__image;

    public function setUp() {

        parent::setUp();

        // Setup our component and fake test controller
        $this->__Collection = new ComponentCollection();        
        $this->__Request = new CakeRequest();
        $this->__Response = new CakeResponse();    
        
        //clear
        clearstatcache();
                
    }
    
    public function tearDown() {
        
        parent::tearDown();
        
        $fileJpg = APP.'Plugin'.DS.'Cloggy'.DS.'webroot'.DS.'test.jpg';
        $fileGif = APP.'Plugin'.DS.'Cloggy'.DS.'webroot'.DS.'test.gif';
        $filePng = APP.'Plugin'.DS.'Cloggy'.DS.'webroot'.DS.'test.png';
        
        /*
         * delete files
         */
        @unlink($fileJpg);
        @unlink($fileGif);
        @unlink($filePng);
        
    }
    
    public function testObject() {   
        $this->__createComponentObject();
        $this->assertTrue(is_a($this->__CloggyImage,'CloggyImageComponent'));        
    }        
    
    public function testAvailableOptions() {
        
        $this->__createComponentObject();
        $commands = $this->__CloggyImage->getAvailableCommands();
        $options = $this->__CloggyImage->getAvailableOptions();
        $supportedImageExts = $this->__CloggyImage->getSupportedImageExt();
        $requiredPhpExts = $this->__CloggyImage->getRequiredPhpExtension();
        
        $this->assertInternalType('array',$commands);
        $this->assertInternalType('array',$options);
        $this->assertInternalType('array',$supportedImageExts);
        $this->assertInternalType('array',$requiredPhpExts);
        
        $this->assertFalse(empty($commands));
        $this->assertFalse(empty($options));
        $this->assertFalse(empty($supportedImageExts));
        $this->assertFalse(empty($requiredPhpExts));
        
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
    
    public function testResizeCropJpg() {
               
        $this->__createComponentObject(array(
            'image' => $this->__getTestImage(),
            'width' => 100,
            'height' => 100,
            'option' => 'auto',
            'command' => 'resize',
            'quality' => 90,
            'save_path' => $this->__getTestImageSavePath('test.jpg')
        ));
        
        //resize image
        $this->__CloggyImage->proceed();
        
        /*
         * check resized image
         */
        $checkResizedImage = $this->__getTestImageSavePath('test.jpg');
        $this->assertTrue(file_exists($checkResizedImage));
        
        //delete files        
        @unlink($checkResizedImage);
        
        $this->__createComponentObject(array(
            'image' => $this->__getTestImage(),
            'width' => 100,
            'height' => 100,
            'option' => 'exact',
            'command' => 'crop',
            'quality' => 90,
            'save_path' => $this->__getTestImageSavePath('test.jpg')
        ));
        
        //crop image
        $this->__CloggyImage->proceed();
        
        /*
         * check resized image
         */
        $checkResizedImage = $this->__getTestImageSavePath('test.jpg');
        $this->assertTrue(file_exists($checkResizedImage));
        
        //delete after test       
        $this->__image = $checkResizedImage;
        
    }
    
    public function testResizeCropGif() {
        
        $this->__createComponentObject(array(
            'image' => $this->__getTestImage('php-icon.gif'),
            'width' => 100,
            'height' => 100,
            'option' => 'auto',
            'command' => 'resize',
            'quality' => 90,
            'save_path' => $this->__getTestImageSavePath('test.gif')
        ));
        
        //resize image
        $this->__CloggyImage->proceed();
        
        /*
         * check resized image
         */
        $checkResizedImage = $this->__getTestImageSavePath('test.gif');
        $this->assertTrue(file_exists($checkResizedImage));
        
        //delete files        
        @unlink($checkResizedImage);
        
        $this->__createComponentObject(array(
            'image' => $this->__getTestImage('php-icon.gif'),
            'width' => 100,
            'height' => 100,
            'option' => 'exact',
            'command' => 'crop',
            'save_path' => $this->__getTestImageSavePath('test.gif')
        ));
        
        //crop image
        $this->__CloggyImage->proceed();
        
        /*
         * check resized image
         */
        $checkResizedImage = $this->__getTestImageSavePath('test.gif');
        $this->assertTrue(file_exists($checkResizedImage));
        
        //delete after test       
        $this->__image = $checkResizedImage;
        
    }
    
    public function testResizeCropPng() {
        
        $this->__createComponentObject(array(
            'image' => $this->__getTestImage('php.png'),
            'width' => 100,
            'height' => 100,
            'option' => 'auto',
            'command' => 'resize',
            'save_path' => $this->__getTestImageSavePath('test.png')
        ));
        
        //resize image
        $this->__CloggyImage->proceed();
        
        /*
         * check resized image
         */
        $checkResizedImage = $this->__getTestImageSavePath('test.png');
        $this->assertTrue(file_exists($checkResizedImage));
        
        //delete files        
        @unlink($checkResizedImage);
        
        $this->__createComponentObject(array(
            'image' => $this->__getTestImage('php.png'),
            'width' => 100,
            'height' => 100,
            'option' => 'exact',
            'command' => 'crop',
            'save_path' => $this->__getTestImageSavePath('test.png')
        ));
        
        //crop image
        $this->__CloggyImage->proceed();
        
        /*
         * check resized image
         */
        $checkResizedImage = $this->__getTestImageSavePath('test.png');
        $this->assertTrue(file_exists($checkResizedImage));
        
        //delete after test       
        $this->__image = $checkResizedImage;
        
    }
    
    public function testErrorImageFile() {
        
        $this->__createComponentObject(array(
            'image' => $this->__getTestImage('test2.jpg'),
            'width' => 100,
            'height' => 100,
            'option' => 'auto',
            'command' => 'resize',
            'quality' => 90,
            'save_path' => $this->__getTestImageSavePath('test.jpg')
        ));
        
        //proceed
        $this->__CloggyImage->proceed();
        
        $checkError = $this->__CloggyImage->isError();
        $errorMsg = $this->__CloggyImage->getErrorMsg();
        
        $this->assertTrue($checkError);
        $this->assertEqual($errorMsg,'Image file not found.');
        
        $this->__createComponentObject(array(
            'image' => $this->__getTestImage('php.zip'),
            'width' => 100,
            'height' => 100,
            'option' => 'auto',
            'command' => 'resize',
            'quality' => 90,
            'save_path' => $this->__getTestImageSavePath('test.jpg')
        ));
        
        //proceed
        $this->__CloggyImage->proceed();
        
        $checkError = $this->__CloggyImage->isError();
        $errorMsg = $this->__CloggyImage->getErrorMsg();
        
        $this->assertTrue($checkError);
        $this->assertEqual($errorMsg,'Cannot open image file.');
        
    }
    
    public function testErrorCommandAndOption() {
        
        /*
         * error option
         */
        $this->__createComponentObject(array(
            'image' => $this->__getTestImage('php.png'),
            'width' => 100,
            'height' => 100,
            'option' => 'test',
            'command' => 'resize',
            'quality' => 90,
            'save_path' => $this->__getTestImageSavePath('test.png')
        ));
        
        //proceed
        $this->__CloggyImage->proceed();
        
        $checkError = $this->__CloggyImage->isError();
        $errorMsg = $this->__CloggyImage->getErrorMsg();
        
        $this->assertTrue($checkError);
        $this->assertEqual($errorMsg,'Option not available.');
        
        /*
         * error command
         */
        $this->__createComponentObject(array(
            'image' => $this->__getTestImage('php.png'),
            'width' => 100,
            'height' => 100,
            'option' => 'auto',
            'command' => 'test',
            'quality' => 90,
            'save_path' => $this->__getTestImageSavePath('test.png')
        ));
        
        //proceed
        $this->__CloggyImage->proceed();
        
        $checkError = $this->__CloggyImage->isError();
        $errorMsg = $this->__CloggyImage->getErrorMsg();
        
        $this->assertTrue($checkError);
        $this->assertEqual($errorMsg,'Command not available.');
    }
    
    public function testErrorDirSavePath() {
        
        /*
         * error command
         */
        $this->__createComponentObject(array(
            'image' => $this->__getTestImage('php.png'),
            'width' => 100,
            'height' => 100,
            'option' => 'auto',
            'command' => 'crop',
            'quality' => 90,
            'save_path' => APP.'plugin'.DS.'Cloggy'.DS.'webroot'.DS.'test'.DS.'test.png'
        ));
        
        //proceed
        $this->__CloggyImage->proceed();
        
        $checkError = $this->__CloggyImage->isError();
        $errorMsg = $this->__CloggyImage->getErrorMsg();
        
        $this->assertTrue($checkError);
        $this->assertEqual($errorMsg,'Image save path not configured or maybe not exists.');
        
    }
    
    public function testErrorImageExt() {
        
        $this->__createComponentObject();
        $this->__CloggyImage->setError('test');
        
        $ext = $this->__CloggyImage->getImageExt();
        $this->assertFalse($ext);
        
    }
    
    private function __getTestImageSavePath($filename='PHP-icon.jpeg') {
        
        $dir = APP.'Plugin'.DS.'Cloggy'.DS.'webroot'.DS;                
        return $dir.$filename;
        
    }
        
    
    private function __getTestImage($filename='PHP-icon.jpeg') {
        return APP.'Plugin'.DS.'Cloggy'.DS.'webroot'.DS.'test'.DS.$filename;
    }
    
    private function __createComponentObject($settings=array()) {
                
        if (empty($this->__Controller)) {
            $this->__Controller = new Controller($this->__Request, $this->__Response);
        }
        
        $this->__Collection->init($this->__Controller);        
        $this->__CloggyImage = new CloggyImageComponent($this->__Collection,$settings);
        
    }
    
}