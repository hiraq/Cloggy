<?php

App::uses('Component', 'Controller');

/**
 * 
 * Manage user access
 * 
 * @author hiraq
 * @name CloggyAclComponent
 * @package Component
 * @subpackage Cloggy
 *
 */
class CloggyAclComponent extends Component {
    
    /**
     * AuthComponent object
     * @var AuthComponent
     */
    public $components = array('Auth');   
    
    /**
     * List of available adapters
     * @var array 
     */
    private $__adapters = array('module','model','url');
    
    /**
     * Requested Controller
     * @var Controller 
     */
    private $__Controller;        
    
    /**
     * Rule object
     * @var CloggyRulesAcl 
     */
    private $__Rule;
    
    /**
     * Module acl object
     * @var CloggyModuleAcl 
     */
    private $__ModuleAcl;
    
    /**
     * Url acl object
     * @var CloggyUrlAcl 
     */
    private $__UrlAcl;
    
    /**
     * Model acl object
     * @var CloggyModelAcl 
     */
    private $__ModelAcl;
    
    /**
     * User data
     * @var array 
     */
    private $__user;
    
    /**
     * Controller callback if failed
     * @var string 
     */
    private $__actionFailedCallback;

    /**
     * Setup adapter
     * 
     * @access public
     * @param ComponentCollection $collection
     * @param array $settings
     */
    public function __construct(ComponentCollection $collection, $settings = array()) {        
        parent::__construct($collection, $settings);  
        
        /*
         * setup user data
         */
        if (empty($this->__user)) {
            $this->__user = $this->Auth->user();            
        }
    } 
    
    /**
     * Controller callback run before controller's beforeFilter
     * > initiate controller and user
     * > check user loggedIn or not
     * > only check ACL if user loggedIn
     * 
     * @param Controller $controller
     */
    public function initialize(Controller $controller) {
        
        /*
         * initialize objects
         */
        parent::initialize($controller);
        
        App::uses('CloggyRulesAcl', 'Cloggy.Controller/Component/Acl');        
        $this->__Controller = $controller;                             
        
        //setup rules
        $this->setRules();        
        
    }
    
    /**
     * Check module user access
     * Only for checking module 
     * @param Controller $controller
     */
    public function startup(Controller $controller) {
        
        parent::startup($controller);                
                
        //run acl for module
        $this->proceedAcl();
        
    } 
    
    public function proceedAcl($adapter='module') {
        
        $aco = $this->__generateAco($adapter);
        $adapterObject = $this->__generateAdapter($adapter);
        
    }
    
    /**
     * Setup rules
     */
    public function setRules() {                
        $this->__Rule = new CloggyRulesAcl();   
        $this->__Rule->setUpController($this->__Controller);
        $this->__Rule->init();           
    }
    
    /**
     * Set user data
     * @param array $data
     */
    public function setUserData($data) {
        $this->__user = $data;
    }
    
    /**
     * Setup controller callback if acl failed
     * @param string $action
     */
    public function setFailedCallBack($action) {        
        $this->__actionFailedCallback = $action;        
    }
    
    /**
     * Reset user data
     */
    public function clearUserData() {
        $this->__user = array();
    }
    
    /**
     * Get adapter object
     * @param string $adapter
     * @return object
     */
    public function getAdapterObject($adapter) {
        return $this->__generateAdapter($adapter);
    }
    
    /**
     * Get aco object
     * @param string $adapter
     * @return string
     */
    public function getAco($adapter) {
        return $this->__generateAco($adapter);
    }
    
    /**
     * Get controller object
     * @return Controller
     */
    public function getControllerObject() {
        return $this->__Controller;
    }
    
    /**
     * Get user data
     * @return array
     */
    public function getUserData() {
        return $this->__user;
    }
    
    /**
     * Get rule object
     * @return CloggyRulesAcl
     */
    public function getRuleObject() {
        return $this->__Rule;
    }    
    
    /**
     * Get requested aco object
     * @param string $adapter [optional]
     * @return string
     */
    private function __generateAco($adapter='module') {
        
        //setup requested  aco
        $this->__Rule->setUpAco($adapter);
        
        $aco = $this->__Rule->getAco();
        return $aco;
        
    }
    
    /**
     * Generate adapter object
     * @param string $adapter
     * @return boolean|object
     */
    private function __generateAdapter($adapter) {
        
        if (in_array($adapter,$this->__adapters)) {
            
            $className = 'Cloggy'.ucfirst(strtolower($adapter)).'Acl';
            
            /*
             * initialize acl module
             */
           App::uses($className, 'Cloggy.Controller/Component/Acl');
           $Adapter = new $className();
           $Adapter->initialize($this);
           
           return $Adapter;
            
        }
        
        return false;
        
    }

}