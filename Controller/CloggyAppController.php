<?php

class CloggyAppController extends AppController {

    public $helpers = array(
        'Html',
        'Form',
        'Cloggy.CloggyAsset',
        'Cloggy.CloggyMenus',
        'Session',
        'Paginator' => array(
            'className' => 'Cloggy.CloggyPaginator'
        ),
        'Js'
    );
    public $components = array(
        'Security',
        'Session',
        'Auth',
        'RequestHandler',
        'Cloggy.CloggyModuleInfo',
        'Cloggy.CloggyModuleMenu',
        'Cloggy.CloggyModuleInstaller',
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
        
        //load boostrap.php from module/config
        $this->__module_bootstrap();

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
            
            //load acl for requested module
            $this->__cloggyAclModule();
            
        }   
        
        /*
         * set deny to all controller and actions
         * except for home controller
         */
        if ($this->request->params['controller'] != 'home') {
            $this->Auth->deny('*');
        }
        
        //custom security black hole callback
        $this->Security->blackHoleCallback = 'callbackBlackHole';       
    }
    
    public function callbackBlackHole($type) {
        $this->autoRender = false;    
        $this->layout = 'Cloggy.cloggy_blackhole_layout';
        
        switch($type) {
            
            case 'auth':
                $message = __d('cloggy','Security Auth error. Request has been black holed.');
                break;
            
            case 'csrf':
                $message = __d('cloggy','Security CSRF error.');
                break;
            
            case 'get':
            case 'post':
            case 'put':
            case 'delete':
                $message = __d('cloggy','HTTP method restriction failure');
                break;
            
            case 'secure':
                $message = __d('cloggy','SSL method restriction failure');
                break;
            
            default:
                $message = $type;
                break;
            
        }
        
        $this->set(compact('message'));        
        $this->render('Cloggy.Elements/cloggy_blackhole');
    }
    
    /**
     * ACL callback when failed
     */
    public function callbackAcl() {
        
        $this->Session->setFlash(
                __d('cloggy','You do not have permission to access that page.'),
                'default',array('class' => 'alert'),'dashNotif');
        
        $this->redirect($this->_base);
    }
    
    /**
     * ACL by url
     */
    private function __cloggyAclUrl() {        
        $this->__cloggyAcl('url');        
    }
    
    /**
     * ACL by module
     */
    private function __cloggyAclModule() {        
        $this->__cloggyAcl('module');        
    }
    
    /**
     * Run CloggyAclComponent check for ARO and ACO
     * @param string $adapter
     */
    private function __cloggyAcl($adapter) {
        
        $this->CloggyAcl->generateAro();
        $this->CloggyAcl->proceedAcl($adapter);
        
        $isAllowed = $this->CloggyAcl->isAroAllowed();
        
        /*
         * if requested ACO by ARO denied then 
         * run callback
         */
        if (!$isAllowed) {
            $this->CloggyAcl->proceedCallback();
        }
        
    }

    /**
     *  Setup dashboard menus
     */
    private function __cloggyMenus() {

        /*
         * setup menus
         */
        $this->CloggyModuleMenu->menus('cloggy', array(
            'dashboard' => CloggyCommon::urlPath('dashboard'),
            'logout' => CloggyCommon::urlPath('logout'),
        ));
    }

    /**
     * Set modules when module url detected
     */
    private function __cloggyModuleRequested() {                

        //generate modules
        $this->CloggyModuleInfo->setExcluded('ModuleTest');
        $this->CloggyModuleInfo->modules();
        $modules = $this->CloggyModuleInfo->getModules();
        $brokenModules = $this->CloggyModuleInfo->getModuleBrokenDeps();
        
        //set global variable
        $this->set(compact('modules'));
        $this->set(compact('brokenModules'));

        /*
         * check if requested params is module request
         * > change layout
         */
        if (isset($this->request->params['isCloggyModule'])
                && $this->request->params['isCloggyModule'] == 1) {

            /*
             * > change layout
             * > set requested module variable
             * > set module name
             */
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
    
    /**
     * Load bootstrap.php from module/config
     */
    private function __module_bootstrap() {
        
        if (!empty($this->_requestedModule)) {
            
            /*
             * setup path
             */
            $moduleName = Inflector::camelize($this->_requestedModule);
            $modulePath = CLOGGY_PATH_MODULE.$moduleName.DS;
            $moduleBoostrapFile = $modulePath.'Config'.DS.'bootstrap.php';
            
            /*
             * if boostrap file exists then load it
             */
            if (file_exists($moduleBoostrapFile)) {
                require_once $moduleBoostrapFile;
            }
            
        }
        
    }

    /**
     * Auth component settings
     */
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

        $this->Auth->authError = __d('cloggy','Identify yourself!');
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