<?php

App::uses('Component', 'Controller');
App::uses('File', 'Utility');
App::uses('Folder', 'Utility');

class CloggyModuleInstallerComponent extends Component {
    
    public $components = array('Session');
    
    private $__Controller;
    private $__requestedModule;    
    private $__base;
    
    /**
     * Initialize component
     * @param Controller $controller
     */
    public function initialize(Controller $controller) {
        parent::initialize($controller);
        $this->__Controller = $controller;  
        $this->__base = '/' . Configure::read('Cloggy.url_prefix');
    }
    
    /**
     * Run after controller beforeFilter method
     * @param Controller $controller
     */
    public function startup(Controller $controller) {
        
        parent::startup($controller);        
        
        if (isset($controller->request->params['name'])) {
            $this->__requestedModule = Inflector::camelize($controller->request->params['name']);
            $this->__needInstall();            
        }
                
    }  
    
    /**
     * Automatically create .installed file
     * after controller executes requested action
     * 
     * @param Controller $controller
     */
    public function beforeRender(Controller $controller) {
        
        parent::beforeRender($controller);
        
        if (!empty($this->__requestedModule)) {
         
            $modulePath = CLOGGY_PATH_MODULE.$this->__requestedModule.DS;  
            $modulePathInstalled = $modulePath.'.installed';
            $moduleInstallController = $modulePath.'Controller'.DS.$this->__requestedModule.'InstallController.php';
            $requested = $this->__Controller->request->params['controller'];
            $moduleInstallControllerUri = Inflector::underscore($this->__requestedModule).'_install';
            
            if (!file_exists($modulePathInstalled) 
                    && file_exists($moduleInstallController) 
                    && $requested == $moduleInstallControllerUri) {
                                              
                //install module finish
                $install = $this->finishInstall($this->__requestedModule);                
                
                /*
                 * check if installation completed or not
                 */
                if (!$install) {
                    
                    $this->Session->setFlash(
                        __d('cloggy','This module cannot be install, please check folder permission for this module.'),
                        'default',array('class' => 'alert'),'dashNotif');

                    $this->__Controller->redirect($this->__base);exit();
                    
                }
                
            }
            
        }        
        
    }
    
    /**
     * Finish install, set .installed file on
     * module path
     * 
     * @param string $module
     * @return boolean
     */
    public function finishInstall($module) {
        
        $modulePath = CLOGGY_PATH_MODULE.$module.DS;
        $modulePathInstalled = $modulePath.'.installed';
        
        $folder = new Folder();
        $folder->chmod($modulePath,0755,true);
        
        $file = new File($modulePathInstalled);
        return $file->create();
        
    }
    
    /**
     * Check if requested module need to install
     */
    private function __needInstall() {
        
        $modulePath = CLOGGY_PATH_MODULE.$this->__requestedModule.DS;         
        $modulePathInstalled = $modulePath.'.installed';
        $moduleInstallController = $modulePath.'Controller'.DS.$this->__requestedModule.'InstallController.php';
        $moduleInstallControllerUri = Inflector::underscore($this->__requestedModule).'_install';
        
        if (!file_exists($modulePathInstalled) 
                && file_exists($moduleInstallController)
                && $this->__Controller->request->params['controller'] != $moduleInstallControllerUri) {
            
            $this->Session->setFlash(
                __d('cloggy','This module need to install.'),
                'default',array('class' => 'alert'),'dashNotif');
        
            $this->__Controller->redirect($this->__base);
            
        }
        
    }
    
}