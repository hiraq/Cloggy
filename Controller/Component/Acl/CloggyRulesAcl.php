<?php

class CloggyRulesAcl {        
    
    /**
     * Requested controller object
     * @var null|Controller
     */
    private $__Controller;
    
    /**
     * CloggyUserPerm model
     * @var CloggyUserPerm 
     */
    private $__CloggyUserPerm;
    
    /**
     * Requested controller name
     * @var null|string 
     */
    private $__requestedController;
    
    /**
     * Requested action name
     * @var null|string
     */
    private $__requestedAction;
    
    /**
     * Requested url string
     * @var null|string 
     */
    private $__requesterUrl;
    
    /**
     * Get ACO based on requested properties (controller/action/url)
     * @var null|string 
     */
    private $__aco;           
    
    /**
     * Initiate process
     */
    public function init() {
        
        if (is_a($this->__Controller,'Controller')) {
            
            /*
             * setup requested properties
             */
            $this->__requestedController = $this->__Controller->request->params['controller'];            
            $this->__requestedAction = $this->__Controller->request->params['action'];            
            $this->__requesterUrl = $this->__Controller->request->url;  
            
            /*
             * generate user perm model
             */                        
            $this->__CloggyUserPerm = ClassRegistry::init('Cloggy.CloggyUserPerm');
            
            
        }
        
    }       
    
    /**
     * Setup controller object
     * @param Controller $controller
     */
    public function setUpController(Controller $controller) {
        $this->__Controller = $controller;
    }
    
    /**
     * Setup ACO
     * @param string $adapter [optional]
     */
    public function setUpAco($adapter='module') {
        
        switch($adapter) {
            
            case 'url':
                $this->__aco = $this->__requesterUrl;                
                break;
            
            default:
                $this->__aco = $this->__requestedController.'/'.$this->__requestedAction;
                break;
            
        }
        
    }        
    
    /**
     * Reset requested properties
     */
    public function reset() {
        $this->__aco = null;
        $this->__requestedAction = null;
        $this->__requestedController = null;
        $this->__requesterUrl = null;        
        $this->__CloggyUserPerm = null;
    }
    
    /**
     * Get rules by requested aco object and adapter
     * @param string $aco
     * @param string $adapter
     * @return boolean|array
     */
    public function getRulesByAcoAdapter($aco,$adapter) {
        
        if (is_a($this->__CloggyUserPerm,'CloggyUserPerm')) {
            
            return $this->__CloggyUserPerm->find('all',array(
                'contain' => array('CloggyUserRole'),
                'conditions' => array(
                    'CloggyUserPerm.aco_object' => $aco,
                    'CloggyUserPerm.aco_adapter' => $adapter,
                )
            ));
            
        }
        
        return false;
    }
    
    /**
     * Get aco object
     * @return string
     */
    public function getAco() {
        return $this->__aco;
    }
    
    /**
     * Get model
     * @return CloggyUserPerm
     */
    public function getCloggyUserPermModel() {
        return $this->__CloggyUserPerm;
    }
    
    /**
     * Get controller object
     * @return Controller
     */
    public function getController() {
        return $this->__Controller;
    }
    
    /**
     * Get current requested controller name
     * @return string
     */
    public function getRequestedController() {
        return $this->__requestedController;
    }
    
    /**
     * Get current requested action name
     * @return string
     */
    public function getRequestedAction() {
        return $this->__requestedAction;
    }
    
    /**
     * Get current requested url
     * @return string
     */
    public function getRequestedUrl() {
        return $this->__requesterUrl;
    }
    
}