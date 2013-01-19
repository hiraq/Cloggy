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
     * CloggyUser model object
     * @var CloggyUser  
     */
    private $__CloggyUser;
    
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
     * Rules
     * @var array 
     */
    private $__rules;

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

}