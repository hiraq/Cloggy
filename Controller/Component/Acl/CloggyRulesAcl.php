<?php

class CloggyRulesAcl {
    
    /**
     * Requested controller object
     * @var null|Controller
     */
    private $__Controller;
    
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
     * Setup controller object
     * @param Controller $controller
     */
    public function setUpController(Controller $controller) {
        $this->__Controller = $controller;
    }        
    
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
            
        }
        
    }        
    
    /**
     * Reset requested properties
     */
    public function reset() {
        $this->__requestedAction = null;
        $this->__requestedController = null;
        $this->__requesterUrl = null;
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