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
   * Adapter object
   * @access private
   * @var CloggyModuleAcl
   */
  private $__CloggyModuleAcl;

  /**
   * Controller object as ACO
   * @access private
   * @var Controller
   */
  private $__Controller;

  /**
   * User permission role as ARO
   * @var string
   */
  private $__userRole;

  /**
   * Flag if user has access or not
   * @access private
   * @var boolean
   */
  private $__hasAccessToModule = false;

  /**
   * Method name for callback if user doesn't 
   * have any permission
   * 
   * @access private
   * @var string
   */
  private $__callbackFailed;

  /**
   * Setup adapter
   * 
   * @access public
   * @param ComponentCollection $collection
   * @param array $settings
   */
  public function __construct(ComponentCollection $collection, $settings = array()) {

    parent::__construct($collection, $settings);
    App::uses('CloggyModuleAcl', 'Cloggy.Controller/Component/Acl');

    $this->__CloggyModuleAcl = new CloggyModuleAcl();
  }

  /**
   * Auto check each controller for permissions after 'beforeFilter' method called
   * 
   * @see Component::startup()
   * @access public
   */
  public function startup(Controller $controller) {

    //merging controller data after beforeFilter method
    $this->__Controller = $controller;

    if (isset($this->__Controller->cloggyModuleAccess)) {

      $this->__CloggyModuleAcl->setRules($this->__Controller->cloggyModuleAccess);

      /*
       * only for registered actions
       */
      $check = $this->__CloggyModuleAcl->check($this->__userRole, $this->__Controller, $this->__Controller->params['action']);
      $this->__hasAccessToModule = !is_null($check) ? $check : false;

      if (!$this->__hasAccessToModule && method_exists($this->__Controller, $this->__callbackFailed)) {
        $action = $this->__callbackFailed;
        $this->__Controller->$action();
      }
    }
  }

  /**
   * Set callback method if user doesn't have 
   * any permission
   * 
   * @access public
   * @param string $action
   */
  public function setCallbackIfFailed($action) {
    $this->__callbackFailed = $action;
  }

  /**
   * Set user role as ARO
   * @access public
   * @param string $role
   */
  public function setUserRole($role) {
    $this->__userRole = $role;
  }

  /**
   * Check if user can access module or not
   * 
   * @access public
   * @return boolean
   */
  public function isUserCanAccessModule() {
    return $this->__hasAccessToModule;
  }

}