<?php

App::uses('CloggyAppController', 'Cloggy.Controller');
App::uses('Xml','Utility');
App::uses('Hash','Utility');

class CloggyBlogImportController extends CloggyAppController {
    
    public $uses = array(
        'CloggyBlogPost',
        'CloggyBlogCategory',
        'CloggyBlogTag',
        'CloggyBlogMedia',
        'Cloggy.CloggyValidation'
    );  
    
    private $__CloggyFileUpload;
    private $__CloggyBlogImport;
    
    public function beforeFilter() {
        
        parent::beforeFilter();   
        
        $this->__CloggyFileUpload = $this->Components->load('Cloggy.CloggyFileUpload');
        
        $this->__CloggyBlogImport = $this->Components->load('CloggyBlogImport');
        $this->__CloggyBlogImport->buildPath();
        
    }
    
    public function index() {
        //pass
    }
    
    public function wordpress() {
        
        if ($this->request->is('post')) {
            
            /*
             * validate file upload
             */
            $this->CloggyValidation->set($this->request->data['CloggyBlogImport']);
            $this->CloggyValidation->validate = array(
                'wordpress_xml' => array(
                    'ext' => array(
                        'rule' => array('extension',array('xml')),
                        'message' => __d('cloggy','You must upload xml file')
                    )
                )
            );
            
            if ($this->CloggyValidation->validates()) {
                
                /*
                 * setup import files
                 */
                $this->__CloggyFileUpload->settings(array(
                    'allowed_types' => array('xml'),
                    'field' => 'wordpress_xml',
                    'data_model' => 'CloggyBlogImport',
                    'folder_dest_path' => APP.'Plugin'.DS.'Cloggy'.DS.'webroot'.DS.'uploads'.DS.'CloggyBlog'.DS.'import'.DS
                ));
                
                //upload xml
                $this->__CloggyFileUpload->proceedUpload();
                
                //check error upload
                $checkUploadError = $this->__CloggyFileUpload->isError();
                
                /*
                 * if error then back to original view
                 * if not then render new view, continue with download and import
                 * data and files
                 */
                if ($checkUploadError) {
                    $this->set('error',$this->__CloggyFileUpload->getErrorMsg());
                } else {
                    
                    $xmlUploadedData = $this->__CloggyFileUpload->getUploadedData();
                    $xmlFile = $xmlUploadedData['dirname'].DS.$xmlUploadedData['basename'];
                    
                    //read wordpress xml data
                    $xml = Xml::toArray(Xml::build($xmlFile));      
                    
                    /*
                     * if option enabled
                     */
                    if (isset($this->request->data['CloggyBlogImport']['wordpress_import_options'])) {
                        $this->__CloggyBlogImport->setupOptions($this->request->data['CloggyBlogImport']['wordpress_import_options']);
                    }
                    
                    //setup imported data
                    $this->__CloggyBlogImport->setupData($xml);
                    
                    //genereate adapter
                    $this->__CloggyBlogImport->generate();
                    
                    /*
                     * check if given data is valid based on adapter
                     */
                    $checkValidData = $this->__CloggyBlogImport->isValidImportedData();
                    if ($checkValidData) {
                        
                        $checkImport = $this->__CloggyBlogImport->import();
                        if ($checkImport) {
                            $this->Session->setFlash(__d('cloggy','Data imported'), 'default', array(), 'success');
                        }
                        
                        
                    } else {
                        $this->set('error',__d('cloggy','Given data is not valid.'));
                    }
                    
                }
                
            } else {
                $this->set('errors', $this->CloggyValidation->validationErrors);
            }
            
        }
        
        $this->set('title_for_layout',__d('cloggy','Import Post From Wordpress'));
        
    }
    
}