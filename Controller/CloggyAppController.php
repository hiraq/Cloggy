<?php

class CloggyAppController extends AppController {

    public $helpers = array(
        'Html',
        'Cloggy.CloggyAsset',
        'Cloggy.CloggyMenus',
        'Session',
        'Paginator' => array(
            'className' => 'Cloggy.CloggyPaginator'
        )
    );
    public $components = array(
        'Session',
        'Auth',
        'Cloggy.CloggyModuleInfo',
        'Cloggy.CloggyModuleMenu',
        'Cloggy.CloggyAcl',
        'Paginator'
    );
    public $layout = 'Cloggy.cloggy_layout';
    protected $_user;
    protected $_base;
    protected $_requestedModule;

    public function beforeFilter() {

        parent::beforeFilter();

        //base url for plugin
        $this->_base = '/' . Configure::read('Cloggy.url_prefix');

        //load menus
        $this->__cloggyMenus();

        //load auth
        $this->__authSettings();

        //load requested modules
        $this->__cloggyModuleRequested();

        /*
         * check if user has loggedIn
         */
        $user = $this->Auth->user();
        if ($user) {                        
        
            $this->_user = $user;
            
            //set acl callback if failed
            $this->CloggyAcl->setFailedCallBack('callbackAcl');
        
            //load acl for requested url
            $this->__cloggyAclUrl();
            
        }                
                
    }
    
    public function callbackAcl() {
        $this->Session->setFlash('You do not have permission to access that page.','default',array(),'aclNotifSuccess');
        $this->redirect($this->_base);
    }
    
    private function __cloggyAclUrl() {
        
        $this->CloggyAcl->generateAro();
        $this->CloggyAcl->proceedAcl('url');
        
        $isAllowed = $this->CloggyAcl->isAroAllowed();
        
        if (!$isAllowed) {
            $this->CloggyAcl->proceedCallback();
        }
        
    }

    private function __cloggyMenus() {

        /*
         * setup menus
         */
        $this->CloggyModuleMenu->menus('cloggy', array(
            'dashboard' => CloggyCommon::urlPath('dashboard'),
            'logout' => CloggyCommon::urlPath('logout'),
        ));
    }

    private function __cloggyModuleRequested() {                

        //generate modules
        $this->CloggyModuleInfo->modules();
        $modules = $this->CloggyModuleInfo->getModules();

        //set global variable
        $this->set(compact('modules'));

        /*
         * check if requested params is module request
         * > change layout
         */
        if (isset($this->request->params['isCloggyModule'])
                && $this->request->params['isCloggyModule'] == 1) {

            $this->layout = 'cloggy_module_layout';
            $this->_requestedModule = $this->request->params['name'];
            $this->set('moduleName', $this->request->params['name']);

            $modulesMenus = array();
            if (!empty($modules)) {

                foreach ($modules as $module => $info) {
                    $link = Inflector::underscore($module);
                    $modulesMenus[$module] = CloggyCommon::urlModule($link);
                }

                /*
                 * switch modules menu
                 */
                $this->CloggyModuleMenu->add('cloggy', array(
                    'Modules' => $modulesMenus
                ));
            }
        }
    }

    private function __authSettings() {

        $this->Auth->authenticate = array(
            'Form' => array(
                'userModel' => 'CloggyUser',
                'scope' => array('CloggyUser.user_status' => 1),
                'fields' => array(
                    'username' => 'user_name',
                    'password' => 'user_password'
                )
            )
        );

        $this->Auth->authError = 'Identify yourself!';
        $this->Auth->loginAction = array(
            'controller' => 'home',
            'action' => 'login',
            'plugin' => 'cloggy'
        );
        $this->Auth->loginRedirect = array(
            'controller' => 'dashboard',
            'action' => 'index',
            'plugin' => 'cloggy'
        );
        $this->Auth->logoutRedirect = array(
            'controller' => 'home',
            'action' => 'login',
            'plugin' => 'cloggy'
        );
    }

}