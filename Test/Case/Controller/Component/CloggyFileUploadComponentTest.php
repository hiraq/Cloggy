<?php

App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('Controller', 'Controller');
App::uses('AppController', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('ComponentCollection', 'Controller');
App::uses('CloggyFileUploadComponent', 'Cloggy.Controller/Component');

class TestController extends AppController {
    
    public function testCallback() {
        echo 'tested.';
    }
    
}

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
        
        //clear
        clearstatcache();
                
    }
    
    public function tearDown() {
        parent::tearDown();
        
        $testFileCopy = APP.'Plugin'.DS.'Cloggy'.DS.'webroot'.DS.'PHP-icon-copy.jpeg';
        
        if (file_exists($testFileCopy)) {
            @unlink($testFileCopy);
        }
        
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
        
        $this->__CloggyFileUpload->settings(array(
            'folder_dest_path' => 'test_path2'
        ));
        
        $folderpath = $this->__CloggyFileUpload->getFolderDest();
        $maxfilesize = $this->__CloggyFileUpload->getMaxFileSize();
        
        $this->assertEqual($folderpath,'test_path2');
        $this->assertEqual($maxfilesize,300000);
        
    }
    
    public function testFileInfo() {
                
        $this->__createComponentObject();
        $fileinfo = $this->__CloggyFileUpload->getFileInfo(__FILE__);
        $fileext = $this->__CloggyFileUpload->getFileExt(__FILE__);
        
        $this->assertInternalType('array',$fileinfo);
        $this->assertInternalType('string',$fileext);
        $this->assertFalse(empty($fileinfo));
                
    }
    
    public function testErrorMsg() {
        
        $this->__createComponentObject();
        $this->__CloggyFileUpload->setupError('Test error message.');
        
        $checkError = $this->__CloggyFileUpload->isError();
        $errorMsg = $this->__CloggyFileUpload->getErrorMsg();
        
        $this->assertTrue($checkError);
        $this->assertEqual($errorMsg,'Test error message.');
        
    }
    
    public function testCallback() {
        
        //setup controller
        $this->__Controller = new TestController($this->__Request,$this->__Response);
        $this->__createComponentObject(array(
            'callback' => 'testCallback'
        ));                
        
        $checkCallback = $this->__CloggyFileUpload->isCallbackExists();
        
        $this->assertTrue($checkCallback);
        $this->expectOutputString('tested.');
        
        $this->__CloggyFileUpload->proceedCallback();
        
    }
    
    public function testCopy() {
        
        $testFolder = APP.'Plugin'.DS.'Cloggy'.DS.'webroot'.DS;
        $testFile = APP.'Plugin'.DS.'Cloggy'.DS.'webroot'.DS.'test'.DS.'PHP-icon.jpeg';
        $testFileCopy = APP.'Plugin'.DS.'Cloggy'.DS.'webroot'.DS.'PHP-icon-copy.jpeg';
        
        $this->assertTrue(file_exists($testFile));
        
        $file = new File($testFile);
        $file->copy($testFileCopy);
        
        $this->assertTrue(file_exists($testFileCopy));
        
    }
    
    public function testErrorField() {                
        
        $testFolder = APP.'Plugin'.DS.'Cloggy'.DS.'webroot'.DS.'test'.DS;
        $testFileCopy = APP.'Plugin'.DS.'Cloggy'.DS.'webroot'.DS.'PHP-icon-copy.jpeg';    
        
        $file = new File($testFileCopy);
        $prop = $file->info();
        $filesize = $prop['filesize'];
        
        $uploadData = array(
            'tmp_name' => $testFileCopy,
            'type' => 'image/jpeg',
            'name' => 'PHP-icon-copy.jpeg',
            'error' => 0,
            'size' => $filesize
        );                
        
        //setup form
        $this->__Request->params['form']['test_upload'] = $uploadData;
                
        $this->__createComponentObject(array(
            'folder_dest_path' => $testFolder,
            'allowed_types' => array('jpg','jpeg','png'),
            'max_file_size' => 300000,                        
        ));                
        
        $this->__CloggyFileUpload->proceedUpload();
        
        $checkError = $this->__CloggyFileUpload->isError();
        $errorMsg = $this->__CloggyFileUpload->getErrorMsg();
        
        $this->assertTrue($checkError);
        $this->assertEqual($errorMsg,'Field name not configured.');
        
    }
    
    public function testErrorUploadData() {
        
        $testFolder = APP.'Plugin'.DS.'Cloggy'.DS.'webroot'.DS.'test'.DS;
        $testFileCopy = APP.'Plugin'.DS.'Cloggy'.DS.'webroot'.DS.'PHP-icon-copy.jpeg';    
        
        $file = new File($testFileCopy);
        $prop = $file->info();
        $filesize = $prop['filesize'];
        
        $uploadData = array(
            'tmp_name' => $testFileCopy,
            'type' => 'image/jpeg',
            'name' => 'PHP-icon-copy.jpeg',
            'error' => 0,
            'size' => $filesize
        );                
        
        //setup form
        $this->__Request->params['form']['test_upload'] = $uploadData;
                
        $this->__createComponentObject(array(
            'folder_dest_path' => $testFolder,
            'allowed_types' => array('jpg','jpeg','png'),
            'max_file_size' => 300000,                        
        ));                
        
        $this->assertTrue(isset($this->__Controller->request->params['form']['test_upload']));
        $this->assertTrue(!empty($this->__Controller->request->params['form']['test_upload']));
        
        $this->__CloggyFileUpload->proceedUpload('test_upload2');
        
        $checkError = $this->__CloggyFileUpload->isError();
        $errorMsg = $this->__CloggyFileUpload->getErrorMsg();
        
        $this->assertTrue($checkError);
        $this->assertEqual($errorMsg,'Upload data not available.');    
        
        $this->__createComponentObject(array(
            'folder_dest_path' => $testFolder,
            'allowed_types' => array('jpg','jpeg','png'),
            'max_file_size' => 300000,                        
        ));    
        
        $this->__CloggyFileUpload->proceedUpload('test_upload');
        
        $data = $this->__CloggyFileUpload->getUploadData();
        
        $this->assertEqual($data,$uploadData);
        
    }
    
    public function testFileTypes() {
        
        $testFolder = APP.'Plugin'.DS.'Cloggy'.DS.'webroot'.DS.'test'.DS;
        $testFileCopy = APP.'Plugin'.DS.'Cloggy'.DS.'webroot'.DS.'PHP-icon-copy.jpeg';    
        
        $file = new File($testFileCopy);
        $prop = $file->info();
        $filesize = $prop['filesize'];
        
        $uploadData = array(
            'tmp_name' => $testFileCopy,
            'type' => 'image/jpeg',
            'name' => 'PHP-icon-copy.jpeg',
            'error' => 0,
            'size' => $filesize
        );                
        
        //setup form
        $this->__Request->params['form']['test_upload'] = $uploadData;
                
        $this->__createComponentObject(array(
            'folder_dest_path' => $testFolder,
            'allowed_types' => array('pdf'),
            'max_file_size' => 300000,                        
        ));                
        
        $this->__CloggyFileUpload->proceedUpload('test_upload');
        
        $checkError = $this->__CloggyFileUpload->isError();
        $errorMsg = $this->__CloggyFileUpload->getErrorMsg();
        
        $this->assertTrue($checkError);
        $this->assertEqual($errorMsg,'File extension not allowed.');                
        
    }
    
    public function testFileSize() {
        
        $testFolder = APP.'Plugin'.DS.'Cloggy'.DS.'webroot'.DS.'test'.DS;
        $testFileCopy = APP.'Plugin'.DS.'Cloggy'.DS.'webroot'.DS.'PHP-icon-copy.jpeg';    
        
        $file = new File($testFileCopy);
        $prop = $file->info();
        $filesize = 200;
        
        $uploadData = array(
            'tmp_name' => $testFileCopy,
            'type' => 'image/jpeg',
            'name' => 'PHP-icon-copy.jpeg',
            'error' => 0,
            'size' => $filesize
        );                
        
        //setup form
        $this->__Request->params['form']['test_upload'] = $uploadData;
                
        $this->__createComponentObject(array(
            'folder_dest_path' => $testFolder,
            'allowed_types' => array('jpg','jpeg'),
            'max_file_size' => 100,                        
        ));                
        
        $this->__CloggyFileUpload->proceedUpload('test_upload');
        
        $checkError = $this->__CloggyFileUpload->isError();
        $errorMsg = $this->__CloggyFileUpload->getErrorMsg();
        
        $this->assertTrue($checkError);
        $this->assertEqual($errorMsg,'Requested uploaded file exceed maximum filesize.');
        
    }
    
    public function testUpload() {
        
        //copy file
        $this->__setupFileUpload();
        
        $testFolder = APP.'Plugin'.DS.'Cloggy'.DS.'webroot'.DS.'test'.DS;
        $testFileCopy = APP.'Plugin'.DS.'Cloggy'.DS.'webroot'.DS.'PHP-icon-copy.jpeg';    
        
        $this->assertTrue(file_exists($testFileCopy));
        
        $file = new File($testFileCopy);
        $prop = $file->info();
        $filesize = $prop['filesize'];
        
        $uploadData = array(
            'tmp_name' => $testFileCopy,
            'type' => 'image/jpeg',
            'name' => 'PHP-icon-copy.jpeg',
            'error' => 0,
            'size' => $filesize
        );                
        
        //setup form
        $this->__Request->params['form']['test_upload'] = $uploadData;
        
        $this->__createComponentObject(array(
            'folder_dest_path' => $testFolder,
            'allowed_types' => array('jpg','jpeg','png'),
            'max_file_size' => 300000,                        
        ));
        
        $this->assertTrue(isset($this->__Controller->request->params['form']['test_upload']));
        $this->assertEqual($this->__Controller->request->params['form']['test_upload']['tmp_name'],$testFileCopy);
        $this->assertEqual($this->__Controller->request->params['form']['test_upload']['type'],'image/jpeg');
        $this->assertEqual($this->__Controller->request->params['form']['test_upload']['name'],'PHP-icon-copy.jpeg');
        $this->assertEqual($this->__Controller->request->params['form']['test_upload']['error'],0);
        $this->assertEqual($this->__Controller->request->params['form']['test_upload']['size'],$filesize);
        
        //setup upload data
        $this->__CloggyFileUpload->setupFiles($uploadData);
        $this->__CloggyFileUpload->proceedUpload('test_upload');
                
        $checkError = $this->__CloggyFileUpload->isError();
        $this->assertTrue($checkError);
        
    }
    
    private function __setupFileUpload() {
        
        $testFolder = APP.'Plugin'.DS.'Cloggy'.DS.'webroot'.DS;
        $testFile = APP.'Plugin'.DS.'Cloggy'.DS.'webroot'.DS.'test'.DS.'PHP-icon.jpeg';
        $testFileCopy = APP.'Plugin'.DS.'Cloggy'.DS.'webroot'.DS.'PHP-icon-copy.jpeg';                
        
        $file = new File($testFile);
        $file->copy($testFileCopy);                
        
    }
    
    private function __createComponentObject($settings=array()) {
                
        if (empty($this->__Controller)) {
            $this->__Controller = new Controller($this->__Request, $this->__Response);
        }
        
        $this->__Collection->init($this->__Controller);        
        $this->__CloggyFileUpload = new CloggyFileUploadComponent($this->__Collection,$settings);
        
    }
    
}