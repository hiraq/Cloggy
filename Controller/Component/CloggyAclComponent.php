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
     * Requested Controller
     * @var Controller 
     */
    private $__Controller;
    
    /**
     * List of adapters
     * @var array 
     */
    private $__adapter = array('Module','Model','Url');
    
    /**
     * Rule object
     * @var CloggyRulesAcl 
     */
    private $__Rule;
    
    /**
     * User data
     * @var array 
     */
    private $__user;

    /**
     * Setup adapter
     * 
     * @access public
     * @param ComponentCollection $collection
     * @param array $settings
     */
    public function __construct(ComponentCollection $collection, $settings = array()) {        
        parent::__construct($collection, $settings);                        
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
        
        /*
         * setup Rule
         */
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
     * Reset user data
     */
    public function clearUserData() {
        $this->__user = array();
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

}