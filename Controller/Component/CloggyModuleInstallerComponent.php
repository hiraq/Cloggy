<?php

App::uses('Component', 'Controller');
App::uses('File', 'Utility');

class CloggyModuleInstallerComponent extends Component {
    
    public $components = array('Session');
    
    private $__Controller;
    private $__requestedModule;    
    private $__base;
    
    public function initialize(Controller $controller) {
        parent::initialize($controller);
        $this->__Controller = $controller;  
        $this->__base = '/' . Configure::read('Cloggy.url_prefix');
    }
    
    public function startup(Controller $controller) {
        
        parent::startup($controller);        
        
        if (isset($controller->request->params['name'])) {
            $this->__requestedModule = Inflector::classify($controller->request->params['name']);
            $this->__needInstall();            
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
        
        $file = new File($modulePathInstalled);
        return $file->create();
        
    }
    
    /**
     * Check if requested module need to install
     */
    private function __needInstall() {
        
        $modulePath = CLOGGY_PATH_MODULE.$this->__requestedModule.DS;        
        $modulePathInstalled = $modulePath.'.installed';
        $moduleInstallController = Inflector::underscore($this->__requestedModule).'_install';
        
        if (!file_exists($modulePathInstalled) && $this->__Controller->request->params['controller'] != $moduleInstallController) {
            
            $this->Session->setFlash(
                __d('cloggy','This module need to install.'),
                'default',array('class' => 'alert'),'dashNotif');
        
            $this->__Controller->redirect($this->__base);
            
        }
        
    }
    
}