<?php

App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('Component', 'Controller');

/**
 * Manage image media, available commands:
 * 1. resize
 * 2. crop
 * 
 * @author hiraq 
 * @package Cloggy
 * @subpackage Component
 * @link http://net.tutsplus.com/tutorials/php/image-resizing-made-easy-with-php/
 */
class CloggyImageComponent extends Component {
    
    /**
     * Image content
     * 
     * @access private
     * @var string 
     */
    private $__image;
    
    /**
     * Resized image
     * @access private
     * @var string 
     */
    private $__imageResized;
    
    /**
     * Where to save resized image
     * 
     * @access private
     * @var string 
     */
    private $__imageSavePath;
    
    /**
     *
     * Image quality
     * 
     * @access private
     * @var int 
     */
    private $__imageQuality = 100;
    
    /**
     * Original image width
     * 
     * @access private
     * @var int 
     */
    private $__originalImageWidth;
    
    /**
     * Original image height
     * 
     * @access private
     * @var int 
     */
    private $__originalImageHeight;
    
    /**
     * Optimal width
     * @var int 
     */
    private $__optimalWidth;
    
    /**
     * Optimal height
     * 
     * @access private
     * @var int 
     */
    private $__optimalHeight;
    
    /**
     * Requested width to resize or crop
     * 
     * @access private
     * @var int 
     */
    private $__requestedWidth;
    
    /**
     * Requested height to resize or crop
     * 
     * @access private
     * @var int 
     */
    private $__requestedHeight;
    
    /**
     * Supported image extensions
     * 
     * @access private
     * @var array 
     */
    private $__supportedImageExtension = array('jpg','jpeg','gif','png');
    
    /**
     * Supported command process
     * 
     * @access private
     * @var array 
     */
    private $__supportedCommand = array('crop','resize');
    
    /**     
     * Supported for optimal image
     * @access private
     * @var array 
     */
    private $__supportedImageOptions = array('auto','exact','portrait','landscape','crop');
    
    /**
     * Required php extension
     * 
     * @access private
     * @var array 
     */
    private $__requiredPhpExtension = array('gd');
    
    /**
     *
     * Command, available option:
     * 1. crop
     * 2. resize     
     * 
     * @access private
     * @var string 
     */
    private $__command = 'crop';
    
    /**
     * Used option
     * @access private
     * @var string 
     */
    private $__option = 'auto';        
    
    /**
     * 
     * Check error
     * 
     * @access private
     * @var boolean 
     */
    private $__isError = false;
    
    /**     
     * Setup error message
     * 
     * @access private
     * @var string 
     */
    private $__errorMsg;
    
    /**
     * Setup component settings
     * 
     * @param ComponentCollection $collection
     * @param array $settings [optional]
     */
    public function __construct(\ComponentCollection $collection, $settings = array()) {
        
        parent::__construct($collection, $settings);
        
        /*
         * check required extension
         */
        $checkExtension = $this->isExtensionLoaded();
        if (!$checkExtension) {
            $this->setError('Required extension not loaded, check your php configuration.');
        }
        
        //parse and setup image settings
        $this->__setupSettings($settings);
                
    }
    
    /**
     * Change settinggs
     * @param array $settings
     */
    public function settings($settings) {
        $this->__setupSettings($settings);
    }
    
    /**
     * Open image file
     * @param string $image
     */
    public function open($image) {
        
        $ext = $this->getImageExt($image);  
        $checkError = $this->isError();
        
        if (!$checkError) {
         
            switch ($ext) {
                case 'jpg':
                case 'jpeg':
                    $img = @imagecreatefromjpeg($image);
                    break;
                case 'gif':
                    $img = @imagecreatefromgif($image);
                    break;
                case 'png':
                    $img = @imagecreatefrompng($image);
                    break;
                default:
                    $img = false;
                    break;
            }

            $this->__image = $img;
            
        }                
        
    }
    
    public function proceed() {
        
        $checkError = $this->isError();        
        if (!$checkError) {
            
        }
        
    }
    
    public function proceedResize() {
        
        $checkError = $this->isError();        
        if (!$checkError) {
            
        }
        
    }
    
    public function proceedCrop() {
        
        $checkError = $this->isError();        
        if (!$checkError) {
            
        }
        
    }
    
    /**
     * Setup command
     * @param string $command
     */
    public function setCommand($command) {
        
        $checkError = $this->isError();
        if ($checkError) {
            
            if (!in_array($command,$this->__supportedCommand)) {
                $this->setError('Command not available.');
            } else {
                $this->__command = $command;
            }
            
        }
        
    }
    
    /**
     * Setup option
     * @param string $option
     */
    public function setOption($option) {
        
        $checkError = $this->isError();
        if (!$checkError) {
            
            if (!in_array($option,$this->__supportedImageOptions)) {
                $this->setError('Option not available.');
            } else {
                $this->__option = $option;
            }
            
        }
        
    }
    
    /**
     * Setup original image width and height
     */
    public function setOriginalImageWidthHeight() {
        
        $checkError = $this->isError();
        if (!$checkError) {
            
            if (empty($this->__image) || !$this->__image) {
                $this->setError('Your requested image not found or cannot readed properly.');
            } else {
                
                /*
                 * get original width and height
                 */
                $this->__originalImageWidth = imagesx($this->__image);
                $this->__originalImageHeigt = imagesy($this->__image);
                
            }
            
        }
        
    }
    
    /**
     * Setup optimal width and height image
     */
    public function setOptimalImageWidthHeight() {
        
        $checkError = $this->isError();
        if (!$checkError) {
            
            if (empty($this->__option)) {
                $this->setError('Option not available, set your option first.');
            } else {
                
                /*
                 * get optimal size width and height
                 */
                switch($this->__option) {
                    
                    case 'exact':
                        $this->__optimalWidth = $this->__requestedWidth;
                        $this->__optimalHeight = $this->__requestedHeight;
                        break;
                    
                    case 'portrait':
                        $this->__setOptimalSizeByPortrait();
                        break;
                    
                    case 'landscape':
                        $this->__setOptimalSizeByLandscape();
                        break;
                    
                    case 'crop':
                        $this->__setOptimalSizeByCrop();
                        break;
                    
                    default:
                        $this->__setOptimalSizeByAuto();
                        break;
                    
                }
                
            }
            
        }
        
    }
    
    /**
     * Setup and raise an error error
     * @param string $msg
     */
    public function setError($msg) {
        $this->__isError = true;
        $this->__errorMsg = $msg;
    }
    
    /**
     * Set destination path to save image
     * @param string $path
     */
    public function setPath($path) {
        
        /*
         * if not exists,then create it
         */
        if (!is_dir($path)) {
            
            $folder = new Folder();
            $folder->create($path, 0777);
            
        }
        
        $this->__imageSavePath = $path;
        
    }
    
    /**
     * Set image quality
     * @param int $quality
     */
    public function setImageQuality($quality) {
        $this->__imageQuality = $quality;
    }
    
    /**
     * Get required php extension
     * @return array
     */
    public function getRequiredPhpExtension() {
        return $this->__requiredPhpExtension;
    }
    
    /**
     * Get available options
     * @return array
     */
    public function getAvailableOptions() {
        return $this->__supportedImageOptions;
    }
    
    /**
     * Get available commands
     * @return array
     */
    public function getAvailableCommands() {
        return $this->__supportedCommand;
    }
    
    /**
     * Get supported image extension list
     * @return array
     */
    public function getSupportedImageExt() {
        return $this->__supportedImageExtension;
    }        
    
    /**
     * Get image extenstion
     * 
     * @param File $file
     * @return string|null|boolean
     */
    public function getImageExt($file) {
        
        $checkError = $this->isError();
        
        if (!$checkError) {
         
            $file = new File($file);
            $checkExists = $file->exists();

            if ($checkExists) {
                return $file->ext();
            } else {
                $this->setError('Image file not found.');
                return false;
            }
            
        }        
        
    }
    
    /**
     * Get error message
     * @return string
     */
    public function getErrorMsg() {
        return $this->__errorMsg;
    }
    
    /**
     * Check error flag
     * @return boolean
     */
    public function isError() {
        return $this->__isError;
    }
    
    /**
     * Check if requested extension loaded or not
     * 
     * @uses extension_loaded
     * @param string $ext [optional]
     * @return boolean
     */
    public function isExtensionLoaded($ext='gd') {        
        return extension_loaded($ext);        
    }
    
    /**
     * Finishing process, save resized image to destination folder
     */
    private function __saveImage() {
        
    }
    
    /**
     * Set image save path based on original image path
     */
    private function __setImageAutPath() {
        
    }
    
    /**
     * Setup optimal size for 'auto' option
     */
    private function __setOptimalSizeByAuto() {
                
        if ($this->__originalImageHeight < $this->__originalImageWidth) {
            $this->__setOptimalSizeByLandscape();
        }
        
        if ($this->__originalImageHeight > $this->__originalImageWidth) {
            $this->__setOptimalSizeByPortrait();
        }
        
        if ($this->__requestedHeight < $this->__requestedWidth) {
            $this->__setOptimalSizeByLandscape();
        }
        
        if ($this->__requestedHeight > $this->__requestedWidth) {
            $this->__setOptimalSizeByPortrait();
        }
        
        if (empty($this->__optimalHeight) && empty($this->__optimalWidth)) {
            $this->__optimalWidth = $this->__requestedWidth;
            $this->__optimalHeight = $this->__requestedHeight;
        }
        
    }
    
    /**
     * Setup optimal size if using portrait option
     */
    private function __setOptimalSizeByPortrait() {
        
        $ratio = $this->__originalImageWidth / $this->__originalImageHeight;
        $newWidth = $this->__requestedHeight * $ratio;
        
        $this->__optimalWidth = $newWidth;        
        $this->__optimalHeight = $this->__requestedHeight;
        
    }
    
    /**
     * Setup optimal size if using landscape option
     */
    private function __setOptimalSizeByLandscape() {
        
        $ratio = $this->__originalImageHeight / $this->__originalImageWidth;
        $newHeight = $this->__requestedWidth * $ratio;
        
        $this->__optimalWidth = $this->__requestedWidth;
        $this->__optimalHeight = $newHeight;
        
    }
    
    /**
     * Setup optimal size if using crop option
     */
    private function __setOptimalSizeByCrop() {
        
        $heightRatio = $this->__originalImageHeight / $this->__requestedHeight;
        $widthRatio = $this->__originalImageWidth / $this->__requestedWidth;
        
        if ($heightRatio < $widthRatio) {
            $optimalRatio = $heightRatio;
        } else {
            $optimalRatio = $widthRatio;
        }
        
        $this->__optimalHeight = $this->__originalImageHeight / $optimalRatio;
        $this->__optimalWidth = $this->__originalImageWidth / $optimalRatio;
        
    }
    
    /**
     * Setup settings
     * 
     * @access private
     * @param array $settings
     */
    private function __setupSettings($settings) {
        
        $checkError = $this->isError();
        if (!$checkError) {
         
            /*
             * image to resize or crop
             */
            if (isset($settings['image']) && !empty($settings['image'])) {
                $this->open($settings['image']);
            }  
            
            /*
             * requested width
             */
            if (isset($settings['width']) && is_numeric($settings['width'])) {
                $this->__requestedWidth = $settings['width'];
            }
            
            /*
             * requested height
             */
            if (isset($settings['height']) && is_numeric($settings['height'])) {
                $this->__requestedHeight = $settings['height'];
            }
            
            /*
             * setup command to proceed
             */
            if (isset($settings['command']) && !empty($settings['command'])) {
                $this->setCommand($settings['command']);
            }  
            
            /*
             * setup option to used
             */
            if (isset($settings['option']) && !empty($settings['option'])) {
                $this->setOption($settings['option']);
            }  
            
            /*
             * setup image path
             */
            if (isset($settings['save_path']) && !empty($settings['save_path'])) {
                $this->setPath($settings['save_path']);
            }  
            
            /*
             * setup image quality
             */
            if (isset($settings['quality']) && !empty($settings['quality'])) {
                $this->setImageQuality($settings['quality']);
            }  
            
            //load original image width and height
            $this->setOriginalImageWidthHeight();
            
        }                
        
    }
    
}