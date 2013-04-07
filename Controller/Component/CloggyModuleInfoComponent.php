<?php

App::uses('Component', 'Controller');

/**
 * Manage modules including their info.ini
 * 
 * @author hiraq
 * @name CloggyModuleInfoComponent
 * @package Cloggy
 * @subpackage Component
 */
class CloggyModuleInfoComponent extends Component {
    
    /**
     * Cloggy acl component
     * @var CloggyAclComponent 
     */
    public $components = array('Cloggy.CloggyAcl');

    /**
     * Store registered modules
     * 
     * @access private
     * @var array
     */
    private $__modules = array();
    
    /**
     * Exclude modules to listed
     * @var array 
     */
    private $__excludeds = array();
    
    /**
     * Store broken dependencies
     * @var array 
     */
    private $__moduleDepsBroken = array();

    /**
     * Setup and get all registered modules
     * @access public
     */
    public function modules() {
        $modules = Configure::read('Cloggy.modules');
        if (!empty($modules) && is_array($modules)) {
            foreach ($modules as $module) {
                
                //check if module allowed or not
                $checkModuleAllowed = $this->CloggyAcl->isModuleAllowedByAro($module);   
                $checkModuleExcluded = $this->isModuleExcluded($module);
                
                /*
                 * if module not excluded
                 */
                if (!$checkModuleExcluded) {
                 
                    /*
                     * only list allowed modules
                     */
                   if($checkModuleAllowed) {
                       if (!array_key_exists($module, $this->__modules)) {

                           $this->__configureModuleInfo($module);
                           $this->__modules[$module]['name'] = $this->getModuleName();
                           $this->__modules[$module]['desc'] = $this->getModuleDesc();
                           $this->__modules[$module]['author'] = $this->getModuleAuthor();
                           $this->__modules[$module]['url'] = $this->getModuleUrl();
                           $this->__modules[$module]['dep'] = $this->getModuleDependency(); 
                           $this->__modules[$module]['install'] = $this->isModuleNeedToInstall($module);
                           $this->__modules[$module]['installed'] = $this->isModuleInstalled($module);
                           $this->__modules[$module]['install_link'] = $this->getModuleInstallLink($module);

                           //check dependent
                           $this->__checkDependentModule($module);

                       }
                   }
                    
                }                
                
            }
        }
    }
    
    /**
     * Check if module exists or not
     * @access public
     * @param string $module
     * @return boolean
     */
    public function isModuleExists($module) {
        return array_key_exists($module, $this->__modules);
    }     
    
    /**
     * Check if module need to install or not
     * @param string $module
     * @return boolean
     */
    public function isModuleNeedToInstall($module) {
        
        $modulePath = CLOGGY_PATH_MODULE.$module.DS;
        $moduleInstallerController = $modulePath.'Controller'.DS.Inflector::classify($module).'InstallController.php';
        
        if (file_exists($moduleInstallerController)) {
            
            if (file_exists($modulePath.'.installed')) {
                return false;
            }
            
            return true;
            
        }
        
        return false;
        
    }        
    
    /**
     * Check if module installed
     * @param string $module
     * @return boolean
     */
    public function isModuleInstalled($module) {
        
        $modulePath = CLOGGY_PATH_MODULE.$module.DS;
        return file_exists($modulePath.'.installed');
        
    }
        
    /**
     * Check if module excluded or not
     * @param string $module
     * @return boolean
     */
    public function isModuleExcluded($module) {
        return in_array($module,$this->__excludeds);
    }
    
    /**
     * Exclude module
     * @param string $module
     */
    public function setExcluded($module) {        
        $this->__excludeds[] = $module;
    }
    
    /**
     * Get installer link
     * @param string $module
     * @return string
     */
    public function getModuleInstallLink($module) {
        return CloggyCommon::urlModule(strtolower(Inflector::underscore($module)),Inflector::underscore($module).'_install');
    }

    /**
     * Get all modules
     * @access public
     */
    public function getModules() {
        return $this->__modules;
    }
    
    /**
     * Get list of broken modules
     * @return array
     */
    public function getModuleBrokenDeps() {
        return $this->__moduleDepsBroken;
    }      

    /**
     * Get module info
     * @access public
     * @param string $module
     * @return null|array
     */
    public function getModuleInfo($module) {
        if (array_key_exists($module, $this->__modules)) {
            return $this->__modules[$module];
        } else {
            return null;
        }
    }

    /**
     * Get module name
     * @access public
     * @return string
     */
    public function getModuleName() {
        $name = Configure::read('info.name');
        return $name;
    }

    /**
     * Get module description
     * @access public
     * @return string
     */
    public function getModuleDesc() {
        $desc = Configure::read('info.desc');
        return $desc;
    }

    /**
     * Get module author
     * @access public
     * @return string
     */
    public function getModuleAuthor() {
        $author = Configure::read('info.author');
        return $author;
    }

    /**
     * Get module url
     * @access public
     * @return string
     */
    public function getModuleUrl() {
        $url = Configure::read('info.url');
        return $url;
    }

    /**
     * Get module dependency
     * @access public
     * @return string 
     */
    public function getModuleDependency() {
        $dep = Configure::read('info.dependency');
        return $dep;
    }
    
    /**
     * Check for broken module dependencies
     * @param string $moduleName
     */
    private function __checkDependentModule($moduleName) {
        
        $deps = $this->getModuleDependency();
        
        /*
         * only if module is has dependency with others
         */
        if (!empty($deps) && $deps != '-') {
            
            if(strstr($deps,',')) {
                
                /*
                 * parsing modules
                 */
                $modules = explode(',',trim($deps));
                
                if (!empty($modules)) {
                    
                    foreach($modules as $module) {
                        
                        $module = trim($module);
                        $check = $this->isModuleExists($module);
                        
                        /*
                         * if dependencies module not exists
                         */
                        if (!$check) {
                            $this->__moduleDepsBroken[] = $moduleName;
                        }
                        
                    }
                    
                }
                
                
            }
            
        }
        
    }

    /**
     * Load info.ini for requested module
     * @access private
     * @param string $moduleName
     */
    private function __configureModuleInfo($moduleName) {
        App::uses('IniReader', 'Configure');
        Configure::config('ini', new IniReader(CLOGGY_PATH_MODULE . $moduleName . DS));
        Configure::load('info.ini', 'ini');
    }

}