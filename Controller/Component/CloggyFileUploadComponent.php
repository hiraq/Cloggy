<?php

App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('Component', 'Controller');

class CloggyFileUploadComponent extends Component {
    
    private $__controller;
    private $__allowedFileTypes = array();
    private $__folderDestPath;
    private $__maxFileSize;
    private $__forceDupFile = false;
    private $__autoUpload = false;
    private $__field;
    private $__callbackAfterUpload;
    private $__uploadData;
    private $__isError = false;
    private $__errorMsg = 'An error has detected';
    
    public function __construct(ComponentCollection $collection, $settings = array()) {
        
        $this->__controller = $collection->getController();
        parent::__construct($collection, $settings);
        
        //setup settings        
        $this->__setupSettings($settings);
        
    }        
    
    /**
     * Setup new settings
     * @param array $settings
     */
    public function settings($settings) {
        $this->__setupSettings($settings);
    }
    
    /**
     * Setup $_FILES data
     * Example:
     * <pre>
     *  <code>
     *      <?php
     *          $this->setupFiles($_FILES['field_name']);
     *      ?>         
     *  </code>
     * </pre>     
     * @param array $files
     */
    public function setupFiles($files) {
        $this->__uploadData = $files;
    }
    
    /**
     * Setup error
     * @param string $errorMsg
     */
    public function setupError($errorMsg) {
        $this->__isError = true;
        $this->__errorMsg = $errorMsg;
    }
    
    public function proceedUpload($field=null) {                
        
        
        
    }
    
    /**
     * Upload file to destination folder
     * 
     * @param string $tmp
     * @param string $newfile
     * @return boolean
     */
    public function proceedUploadDirectly($tmp,$newfile) {
        return move_uploaded_file($tmp,$newfile);
    }
    
    /**
     * Run controller callback
     */
    public function proceedCallback() {
        
        $checkCallback = $this->isCallbackExists();
        
        /*
         * if callback exists
         */
        if ($checkCallback) {
            $callback = $this->getCallback();
            $this->__controller->$callback();
        }
        
    }
    
    public function getErrorMsg() {
        return $this->__errorMsg;
    }
    
    /**
     * Get max file size
     * @return int|null
     */
    public function getMaxFileSize() {
        return $this->__maxFileSize;
    }
    
    /**
     * Get allowed file types
     * @return array|null
     */  
    public function getAllowedFileTypes() {
        return $this->__allowedFileTypes;
    }
    
    /**
     * Get folder destination path
     * @return string|null
     */
    public function getFolderDest() {
        return $this->__folderDestPath;
    }
    
    /**
     * Get $_FILES field name
     * @return string
     */
    public function getFieldName() {
        return $this->__field;
    }
    
    /**
     * Get controller callback name
     * @return string
     */
    public function getCallback() {
        return $this->__callbackAfterUpload;
    }
    
    public function isError() {
        return $this->__isError;
    }
    
    /**
     * Check if duplicate file name true or false
     * @return boolean
     */
    public function isForceDupFile() {
        return $this->__forceDupFile;
    }
    
    /**
     * Check if auto upload enabled or not
     * @return boolean
     */
    public function isAutoUpload() {
        return $this->__autoUpload;
    }
    
    /**
     * Check if callback exists or not
     * @return boolean
     */
    public function isCallbackExists() {
        
        $callback =  $this->getCallback();
        if (method_exists($this->__controller,$callback)) {
            return true;
        }
        
        return false;
        
    }   
    
    /**
     * Rewrite filename
     * @param string $filename
     * @return string
     */
    private function __rewriteFile($filename) {                
        
        $exp = explode('.',$filename);
        $fileType = $exp[1];
        $fileName = $exp[0].'_'.uniqid();                
        
        return $fileName.'.'.$fileType;
    }
    
    /**
     * Setup upload settings
     * @param array $settings
     */
    private function __setupSettings($settings) {
        
        if (!empty($settings)) {
            
            /*
             * setup allowed file types
             */
            if (isset($settings['allowed_types'])) {
                $this->__allowedFileTypes = $settings['allowed_types'];
            }
            
            /*
             * setup destination path
             */
            if (isset($settings['folder_dest_path'])) {
                $this->__folderDestPath = $settings['folder_dest_path'];
            }
            
            /*
             * setup max file size
             */
            if (isset($settings['max_file_size'])) {
                $this->__maxFileSize = $settings['max_file_size'];
            }
            
            /*
             * setup force dup filename
             * if this settings is exists, this component always upload requested file
             * to the destination path with modified filename that using uniqid() although
             * there is a same file
             */
            if (isset($settings['force_dup_filename'])) {
                $this->__forceDupFile = $settings['force_dup_filename'];
            }
            
            /*
             * setup auto upload
             * auto upload executed at startup callback, if detected $_FILES and match
             * with $this->request->params['form']['<field_name>'] then automatically
             * uploaded to destination path
             */
            if (isset($settings['auto_upload'])) {
                $this->__autoUpload = $settings['auto_upload'];
            }
            
            /*
             * setup $_FILES['<field>']
             */
            if (isset($settings['field'])) {
                $this->__field = $settings['field'];
            }
            
            /*
             * setup controller method for callback after upload file
             */
            if (isset($settings['callback'])) {
                $this->__callbackAfterUpload = $settings['callback'];
            }
            
        }
        
    }
    
}