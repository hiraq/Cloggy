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
    private $__adapters = array('module','controller','url');
    
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
     * User or role id
     * @var int 
     */
    private $__aroId;
    
    /**
     * available values:
     * > users
     * > roles
     * @var string 
     */
    private $__aroType;
    
    /**
     * Flag if aro has permission or not
     * @var boolean 
     */
    private $__allowed = true;

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
        
        /*
         * only at requested module
         */
        if (isset($this->__Controller->request->params['isCloggyModule'])
                && $this->__Controller->request->params['isCloggyModule'] == 1) {
         
            //setup aro data
            $this->generateAro();

            //run acl for module
            $this->proceedAcl('controller');

            //get flag
            $isAllowed = $this->isAroAllowed();

            /*
             * if not allowed proceed callback
             */
            if (!$isAllowed) {
                $this->proceedCallback();
            }
            
        }                
        
    } 
    
    /**
     * Acl process
     * @param string $adapter
     */
    public function proceedAcl($adapter='module') {
        
        $adapterObject = $this->__generateAdapter($adapter);        
        $aco = $this->getAco($adapter);
        
        /*
         * checking access
         */
        $check = $adapterObject->check(array(
                    'id' => $this->__aroId,
                    'type' => $this->__aroType
                ),$aco);               
        
        //set flag
        $this->__allowed = $check;        
        
    }
    
    /**
     * Run callback if failed
     */
    public function proceedCallback() {
        
        /*
         * check for callback
         * if isset then run it
         */
        if (!empty($this->__actionFailedCallback) 
                && !is_null($this->__actionFailedCallback)) {

            $callback = $this->__actionFailedCallback;
            if (method_exists($this->__Controller,$callback)) {
                $this->__Controller->$callback();
            }

        }
        
    }
    
    /**
     * Get flag allowed or not
     * @return boolean
     */
    public function isAroAllowed() {
        return $this->__allowed;
    }
    
    /**
     * Check a module requested by current aro
     * allowed or not
     * @param string $module
     * @return boolean
     */
    public function isModuleAllowedByAro($module) {
                
        $this->generateAro();
        
        $Perm = ClassRegistry::init('Cloggy.CloggyUserPerm');
        $check = $Perm->checkAroPermission($module,'module',$this->__aroId,$this->__aroType);
        
        return $check;
        
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
     * Generate aro request
     * @param string $type [optional]
     * @return type
     */
    public function generateAro($type='roles') {
        
        $user = $this->getUserData();
        
        /*
         * if user not loggedIn
         */
        if (empty($user)) {            
            $this->proceedCallback();            
        } else {
            
            //set aro type
            $this->__aroType = $type;
            
            /*
             * get aro id by type users or roles
             */
            switch($type) {
                
                case 'users':
                    $this->__aroId = $this->__user['id'];
                    break;
                
                default:
                    
                    if (isset($this->__user['CloggyUserRole'])) {
                        $this->__aroId = $this->__user['CloggyUserRole']['id'];
                    } else {
                        
                        /*
                         * get manually user role data
                         * based on user loggedIn 
                         */
                        $userId = $this->__user['id'];
                        
                        /*
                         * generate cloggy user model
                         */
                        $CloggyUser = ClassRegistry::init('Cloggy.CloggyUser');
                                                
                        $user = $CloggyUser->find('first',array(
                            'contain' => array('CloggyUserRole'),
                            'conditions' => array(
                                'CloggyUser.id' => $userId
                            )
                        ));
                        
                        $this->__aroId = $user['CloggyUserRole']['id'];
                        
                    }
                    
                    break;
                
            }
            
        }
        
    }
    
    /**
     * Get aro id
     * @return int
     */
    public function getAroId() {
        return $this->__aroId;
    }
    
    /**
     * Get aro type
     * @return string
     */
    public function getAroType() {
        return $this->__aroType;
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