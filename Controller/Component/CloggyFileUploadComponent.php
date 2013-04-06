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
    private $__errorMsg;
    private $__filepath;
    private $__uploadSuccessData;
    private $__dataModel;
    
    /**
     * Setup settings
     * 
     * @param ComponentCollection $collection
     * @param array $settings [optional]
     */
    public function __construct(ComponentCollection $collection, $settings = array()) {
        
        $this->__controller = $collection->getController();
        parent::__construct($collection, $settings);
        
        //setup settings        
        $this->__setupSettings($settings);
        
        //setup error message
        $this->__errorMsg = __d('cloggy','An error has detected');
        
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
     * Example:<br />
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
     * Change file permission
     * @param int $mode
     */
    public function setupChmod($mode) {
        
        $checkError = $this->isError();
        
        if (!$checkError) {
            
            //change permission
            @chmod($this->__filepath,$mode);
            
        }
        
    }
    
    /**
     * Setup error
     * @param string $errorMsg
     */
    public function setupError($errorMsg) {
        $this->__isError = true;
        $this->__errorMsg = $errorMsg;
    }
    
    /**
     * Do upload
     * @param string|null $field [optional]
     */
    public function proceedUpload($field=null) {    
        
        //check upload error status
        $this->__proceedUploadErrorStatus();
        
        //check and proceed field
        $this->__proceedField($field);
        
        //check and proceed upload data
        $this->__proceedUploadData();
        
        //check and proceed file types
        $this->__proceedFileTypes();  
        
        //check and proceed file size
        $this->__proceedFileSize();
        
        //check and proceed file name
        $this->__proceedFileName();                
        
        //do upload
        $this->__doUpload();
        
    }
    
    /**
     * Upload file to destination folder
     * 
     * @param string $tmp
     * @param string $newfile
     * @return boolean
     */
    public function proceedUploadDirectly($tmp,$newfile) {           
        return @move_uploaded_file($tmp,$newfile);
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
    
    /**
     * Get uploaded data
     * @return array|null
     */
    public function getUploadedData() {
        return $this->__uploadSuccessData;
    }
    
    /**
     * Get upload data
     * @return array
     */
    public function getUploadData() {
        return $this->__uploadData;
    }
    
    /**
     * Get file info
     * @param string $filepath
     * @return array
     */
    public function getFileInfo($filepath) {
        
        $file = new File($filepath);
        return $file->info();
        
    }
    
    /**
     * 
     * @param string $filepath
     * @return string
     */
    public function getFileExt($filepath) {
        
        $file = new File($filepath);
        return $file->ext();
        
    }
    
    /**
     * Get error message
     * @return string
     */    
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
     * Check and proceed $_FILES[$field]
     * 
     * @access private
     * @param string $field
     */
    private function __proceedField($field) {
        
        //get error status
        $checkError = $this->isError();
        
        if (!$checkError) {
         
            /*
            * reassign $field if null or empty
            */
            if (is_null($field) || empty($field)) {
                $field = $this->getFieldName();
            }

            /*
             * if still empty or null then raise an error
             */
            if (empty($field)) {
                $this->setupError(__d('cloggy','Field name not configured.'));
            } else {
                $this->__field = $field;
            }
        }                          
        
    }
    
    /**
     * Check and proceed upload data files
     */
    private function __proceedUploadData() {
        
        //get error status
        $checkError = $this->isError();
        
        /*
         * only continue if free from error
         */
        if (!$checkError) {
            
            if (empty($this->__uploadData)) {
                
                $field = $this->getFieldName();
                
                /*
                 * check for upload data
                 */
                if (isset($this->__controller->request->params['form'][$field]) 
                        && !empty($this->__controller->request->params['form'][$field])) {

                    //setup upload data
                    $this->setupFiles($this->__controller->request->params['form'][$field]);
                    
                } elseif (isset($this->__controller->request->data[$this->__dataModel][$field]) && 
                        !empty($this->__controller->request->data[$this->__dataModel][$field])) {
                    
                    //setup upload data
                    $this->setupFiles($this->__controller->request->data[$this->__dataModel][$field]);
                    
                } else {
                    $this->setupError(__d('cloggy','Upload data not available.'));
                }
                
            }
            
        }
        
    }
    
    /**
     * Check allowed file types
     */
    private function __proceedFileTypes() {
        
        //get error status
        $checkError = $this->isError();
        
        if (!$checkError) {
            
            //get file extension
            $fileTmpExt = $this->getFileExt($this->__uploadData['name']);
            
            /*
             * if file type not allowed
             */
            if (is_array($this->__allowedFileTypes)
                    && !empty($this->__allowedFileTypes)
                    && !in_array($fileTmpExt, $this->__allowedFileTypes)) {

                //raise an error
                $this->setupError(__d('cloggy','File extension not allowed.'));
            }
            
        }
        
    }        
    
    /**
     * Check filesize
     */
    private function __proceedFileSize() {
        
        //get error status
        $checkError = $this->isError();
        
        if (!$checkError) {                        
            
            if (!empty($this->__maxFileSize) 
                    && $this->__uploadData['size'] > $this->__maxFileSize) {

                //raise an error
                $this->setupError(__d('cloggy','Requested uploaded file exceed maximum filesize.'));
            }
            
        }
        
    }
    
    /**
     * Check file name and setup filepath
     */
    private function __proceedFileName() {
        
        //get error status
        $checkError = $this->isError();
        
        if (!$checkError) {
                        
            $filename = $this->__uploadData['name'];            
            
            if (empty($this->__folderDestPath)) {
                $this->setupError(__d('cloggy','Folder destination not configured.'));
            } else {
                
                /*
                 * create folder if not exists
                 */
                if (!is_dir($this->__folderDestPath)) {
                    
                    /*
                     * create folder
                     */
                    $folder = new Folder();
                    $folder->create($this->__folderDestPath);    
                    $folder->chmod($this->__folderDestPath,0755);
                }
                
                $filepath = $this->__folderDestPath.$filename;
                
                /*
                 * check if exists
                 */
                if (file_exists($filepath)) {
                    
                    /*
                     * raise an error if forDupFile set to false
                     * and there is an existed file
                     */
                    if ($this->__forceDupFile) {
                        $filename = $this->__rewriteFile($filename);
                        $this->__filepath = $this->__folderDestPath.$filename;
                    } else {
                        $this->setupError(__d('cloggy','Cannot upload file due to duplicate file.'));
                    }
                    
                } else {
                    $this->__filepath = $filepath;
                }                                
                
            }
            
        }
        
    }
    
    /**
     * Check for default php error status
     * @link http://www.php.net/manual/en/features.file-upload.errors.php 
     */
    private function __proceedUploadErrorStatus() {
        
        //get error status
        $checkError = $this->isError();
        
        if (!$checkError) {
            
            if ($this->__uploadData['error'] > 0) {
                
                switch($this->__uploadData['error']) {
                    
                    case 1:
                    case 2:
                        $errorMsg = __d('cloggy','The uploaded file exceeds the upload_max_filesize');
                        break;
                    
                    case 3:
                        $errorMsg = __d('cloggy','The uploaded file was only partially uploaded. ');
                        break;
                    
                    case 4:
                        $errorMsg = __d('cloggy','No file was uploaded. ');
                        break;
                    
                    case 6:
                        $errorMsg = __d('cloggy','Missing a temporary folder');
                        break;
                    
                    case 7:
                        $errorMsg = __d('cloggy','Failed to write file to disk');
                        break;
                    
                    case 8:
                        $errorMsg = __d('cloggy','A PHP extension stopped the file upload.');
                        break;
                    
                    default:
                        $errorMsg = __d('cloggy','An error has occured.');
                        break;
                    
                }
                
                //raise an error
                $this->setupError($errorMsg);
                
            }
            
        }
        
    }
    
    /**
     * Do upload process
     */
    private function __doUpload() {
        
        //get error status
        $checkError = $this->isError();
        
        if (!$checkError) {
            
            $tmpName = $this->__uploadData['tmp_name'];
            $uploadFile = $this->proceedUploadDirectly($tmpName, $this->__filepath);
            
            if ($uploadFile) {
                
                //change permission
                $this->setupChmod(0755);
                $file = $this->getFileInfo($this->__filepath);
                
                if (empty($file)) {
                    $this->setupError(__d('cloggy','Failed to upload file.'));
                } else {
                    $this->__uploadSuccessData = $file;
                }
                
            } else {
                $this->setupError(__d('cloggy','Cannot upload file.'));
            }
            
        }
        
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
            
            if (isset($settings['data_model'])) {
                $this->__dataModel = $settings['data_model'];
            }
            
        }
        
    }
    
}