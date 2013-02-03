<?php

/*
 * controller and components
 */
App::uses('Controller', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('ComponentCollection', 'Controller');
App::uses('CloggyAclComponent', 'Cloggy.Controller/Component');

/*
 * models
 */
App::uses('CloggyAppModel', 'Cloggy.Model');
App::uses('CloggyUser', 'Cloggy.Model');

class TestCallbackController extends Controller {        
    
    public $uses = array('Cloggy.CloggyUser');
    
    public function beforeFilter() {
        parent::beforeFilter();        
    }
    
    public function test() {
        echo 'failed!';
    }
    
}

class CloggyAclComponentTest extends CakeTestCase {

    public $fixtures = array(
        'plugin.cloggy.cloggy_user',        
        'plugin.cloggy.cloggy_user_role',
        'plugin.cloggy.cloggy_user_perm'
    );
    
    private $__CloggyAcl;
    private $__CloggyUser;
    private $__Controller;
    private $__TestController;

    public function setUp() {

        parent::setUp();

        // Setup our component and fake test controller
        $Collection = new ComponentCollection();
        $CakeRequest = new CakeRequest();
        $CakeResponse = new CakeResponse();

        $this->__CloggyUser = ClassRegistry::init('CloggyUser');
        $this->__CloggyAcl = new CloggyAclComponent($Collection);
        
        /*
         * main controller
         */
        $this->__Controller = new Controller($CakeRequest, $CakeResponse);
        $this->__Controller->constructClasses();
        
        /*
         * contstruct class for TestController
         */
        $this->__TestController = new TestCallbackController($CakeRequest,$CakeResponse);
        $this->__TestController->constructClasses();
                
    }

    public function testObjects() {        
        $this->assertFalse(empty($this->__Controller));
        $this->assertFalse(empty($this->__CloggyAcl));
        $this->assertTrue(is_a($this->__CloggyUser, 'CloggyUser'));
        $this->assertTrue(is_a($this->__TestController->CloggyUser, 'CloggyUser'));
    }                    
    
    public function testUserData() {
        
        $user = $this->__getUser();
        
        $this->assertFalse(empty($user));                  
        $this->assertArrayHasKey('CloggyUserRole',$user);
        $this->assertArrayHasKey('CloggyUserPermAro',$user);
        $this->assertFalse(empty($user['CloggyUser']));
        $this->assertFalse(empty($user['CloggyUserRole']));
        $this->assertFalse(empty($user['CloggyUserPermAro']));                
        
        $this->__CloggyAcl->setUserData($user);
        $this->__CloggyAcl->initialize($this->__Controller);        
                
        $user = $this->__CloggyAcl->getUserData();        
        $this->assertFalse(empty($user));        
        
        $this->__CloggyAcl->clearUserData();
        $this->__CloggyAcl->initialize($this->__Controller);
        
        $user = $this->__CloggyAcl->getUserData();        
        $this->assertTrue(empty($user));        
        
    }
    
    public function testRulesBasic() {
        
        $user = $this->__getUser();
        
        $this->__CloggyAcl->setUserData($user);
        $this->__CloggyAcl->initialize($this->__Controller);        
        
        $Rule = $this->__CloggyAcl->getRuleObject();
        $this->assertTrue(is_a($Rule,'CloggyRulesAcl'));
                        
        $RuleController = $Rule->getController();
        $Controller = $this->__CloggyAcl->getControllerObject();
        
        $this->assertTrue(is_a($RuleController,'Controller'));
        $this->assertEqual($Controller, $RuleController);
        
        $Rule->init();
        
        $ruleControllerName = $Rule->getRequestedController();
        $ruleActionName = $Rule->getRequestedAction();
        $ruleUrl = $Rule->getRequestedUrl();                
        
        $this->assertTrue(is_null($ruleControllerName));
        $this->assertTrue(is_null($ruleActionName));                   
        
    }
    
    public function testRulesRequest() {
        
        $this->__Controller->request->url = 'controller/action';         
        $this->__Controller->request->params['plugin'] = 'cloggy';
        $this->__Controller->request->params['controller'] = 'cloggy_test_controller';
        $this->__Controller->request->params['action'] = 'action';
        $this->__Controller->request->query['url'] = 'controller/action';
        
        $this->assertEqual($this->__Controller->request->url,'controller/action');
        $this->assertEqual($this->__Controller->request->params['plugin'],'cloggy');
        $this->assertEqual($this->__Controller->request->params['controller'],'cloggy_test_controller');
        $this->assertEqual($this->__Controller->request->params['action'],'action');
        $this->assertEqual($this->__Controller->request->query['url'],'controller/action');
        
        $user = $this->__getUser();        
        $this->__CloggyAcl->setUserData($user);
        $this->__CloggyAcl->initialize($this->__Controller); 
        
        $Rule = $this->__CloggyAcl->getRuleObject();                
        $Rule->init();
        
        $ruleControllerName = $Rule->getRequestedController();
        $ruleActionName = $Rule->getRequestedAction();
        $ruleUrl = $Rule->getRequestedUrl();                
        
        $this->assertEqual($ruleControllerName,$this->__Controller->request->params['controller']);
        $this->assertEqual($ruleActionName,$this->__Controller->request->params['action']);                
        $this->assertEqual($ruleUrl,$this->__Controller->request->query['url']);
        
        $Rule->reset();
        
        $ruleControllerName = $Rule->getRequestedController();
        $ruleActionName = $Rule->getRequestedAction();
        $ruleUrl = $Rule->getRequestedUrl();                
        
        $this->assertTrue(is_null($ruleControllerName));
        $this->assertTrue(is_null($ruleActionName));
        $this->assertTrue(is_null($ruleUrl));
        
    }
    
    public function testRuleAdapter() {
        
        $user = $this->__getUser();
        
        $this->__Controller->request->url = 'url/controller/action';         
        $this->__Controller->request->params['plugin'] = 'cloggy';
        $this->__Controller->request->params['controller'] = 'controller';
        $this->__Controller->request->params['action'] = 'action';
        $this->__Controller->request->query['url'] = 'url/controller/action';
        
        $this->__CloggyAcl->setUserData($user);
        $this->__CloggyAcl->initialize($this->__Controller);
        
        $Rule = $this->__CloggyAcl->getRuleObject();                
        $Rule->init();
        $Rule->setUpAco('controller');
        
        $aco = $Rule->getAco();        
        $this->assertEqual($aco,'controller/action');
                        
        $Rule->setUpAco('url');
        $aco = $Rule->getAco();        
        $this->assertEqual($aco,'url/controller/action');
        
    }
    
    public function testModel() {
        
        $user = $this->__getUser();
        
        $this->__CloggyAcl->setUserData($user);
        $this->__CloggyAcl->initialize($this->__Controller);        
        
        $Rule = $this->__CloggyAcl->getRuleObject();                
        $Rule->init();
        $Rule->setUpAco('controller');
        
        $Model = $Rule->getCloggyUserPermModel();
        $this->assertTrue(is_a($Model,'CloggyUserPerm'));                
        
        $data = $Rule->getRulesByAcoAdapter('controller/action','controller');
        
        $this->assertFalse(empty($data));
        $this->assertCount(3,$data);
        
        $Rule->reset();
        
        $data = $Rule->getRulesByAcoAdapter('controller/action','controller');
        $this->assertFalse($data);
    }
    
    public function testAclAcoAdapter() {
        
        $user = $this->__getUser();
        
        $this->__Controller->request->url = 'url/controller/action';         
        $this->__Controller->request->params['plugin'] = 'cloggy';
        $this->__Controller->request->params['controller'] = 'controller';
        $this->__Controller->request->params['action'] = 'action';
        $this->__Controller->request->query['url'] = 'url/controller/action';
        
        $this->__CloggyAcl->setUserData($user);
        $this->__CloggyAcl->initialize($this->__Controller);
        
        $aco = $this->__CloggyAcl->getAco('controller');
        $this->assertEqual($aco,'controller/action');
        
        $adapter = $this->__CloggyAcl->getAdapterObject('module');        
        $this->assertTrue(is_a($adapter,'CloggyModuleAcl'));
        
        $wrongAdapter = $this->__CloggyAcl->getAdapterObject('test');        
        $this->assertFalse($wrongAdapter);
        
    }
    
    public function testCallback() {
        
        $user = $this->__getUser();
        
        $this->__TestController->request->url = 'url/controller/action';         
        $this->__TestController->request->params['plugin'] = 'cloggy';
        $this->__TestController->request->params['controller'] = 'controller';
        $this->__TestController->request->params['action'] = 'action';
        $this->__TestController->request->query['url'] = 'url/controller/action';
        
        $this->__CloggyAcl->setUserData($user);
        $this->__CloggyAcl->initialize($this->__TestController);
        $this->__CloggyAcl->setFailedCallBack('test');
        
        $this->__CloggyAcl->clearUserData();
        $this->expectOutputString('failed!');                                
        $this->__CloggyAcl->generateAro();
        
    }
    
    public function testAro() {
        
        $user = $this->__getUser();
        
        $this->__Controller->request->url = 'url/controller/action';         
        $this->__Controller->request->params['plugin'] = 'cloggy';
        $this->__Controller->request->params['controller'] = 'controller';
        $this->__Controller->request->params['action'] = 'action';
        $this->__Controller->request->query['url'] = 'url/controller/action';
        
        $this->__CloggyAcl->setUserData($user);
        $this->__CloggyAcl->initialize($this->__Controller);
        $this->__CloggyAcl->generateAro();
                
        $aroId = $this->__CloggyAcl->getAroId();
        $aroType = $this->__CloggyAcl->getAroType();
        
        $this->assertEqual($aroId,1);
        $this->assertEqual($aroType,'roles');
        
        /*
         * delete role and perm
         * test manual get data from database
         */
        unset($user['CloggyUserRole']);
        unset($user['CloggyUserPermAro']);
        
        $this->__CloggyAcl->setUserData($user['CloggyUser']);
        $this->__CloggyAcl->generateAro();
        
        $aroId = $this->__CloggyAcl->getAroId();
        $aroType = $this->__CloggyAcl->getAroType();
        
        $this->assertEqual($aroId,1);
        $this->assertEqual($aroType,'roles');
        
        $this->__CloggyAcl->setUserData($user['CloggyUser']);
        $this->__CloggyAcl->generateAro('users');
        
        $aroId = $this->__CloggyAcl->getAroId();
        $aroType = $this->__CloggyAcl->getAroType();
        
        $this->assertEqual($aroId,1);
        $this->assertEqual($aroType,'users');                
    }
    
    public function testStartup() {
        
        $user = $this->__getUser();
        
        $this->__TestController->request->url = 'url/controller/action';         
        $this->__TestController->request->params['plugin'] = 'cloggy';
        $this->__TestController->request->params['controller'] = 'controller';
        $this->__TestController->request->params['action'] = 'action';
        $this->__TestController->request->params['isCloggyModule'] = 1;
        $this->__TestController->request->query['url'] = 'url/controller/action';        
        
        $this->__CloggyAcl->setUserData($user);
        $this->__CloggyAcl->initialize($this->__TestController);
        $this->__CloggyAcl->setFailedCallBack('test');        
        $this->__CloggyAcl->startup($this->__TestController);
        
        $isAllowed = $this->__CloggyAcl->isAroAllowed();
        $this->assertTrue($isAllowed);
        
        $user = $this->__getUser(2);
        $this->__CloggyAcl->clearUserData();
        $this->__CloggyAcl->setUserData($user);
        $this->__CloggyAcl->startup($this->__TestController);
        
        $isAllowed = $this->__CloggyAcl->isAroAllowed();
        $this->expectOutputString('failed!');
        $this->assertFalse($isAllowed);
        
    }
    
    public function testModuleIsAllowed() {
        
        $user = $this->__getUser();
        
        $this->__Controller->request->url = 'url/controller/action';         
        $this->__Controller->request->params['plugin'] = 'cloggy';
        $this->__Controller->request->params['controller'] = 'controller';
        $this->__Controller->request->params['action'] = 'action';
        $this->__Controller->request->params['name'] = 'TestModule';
        $this->__Controller->request->query['url'] = 'url/controller/action';
        
        $this->__CloggyAcl->setUserData($user);
        $this->__CloggyAcl->initialize($this->__Controller);
        $this->__CloggyAcl->generateAro();
        
        $checkModule = $this->__CloggyAcl->isModuleAllowedByAro('TestModule');
        $this->assertFalse($checkModule);
        
        $checkModule = $this->__CloggyAcl->isModuleAllowedByAro('TestModule2');
        $this->assertTrue($checkModule);
        
    }
    
    private function __getUser($id=1) {
        return $this->__CloggyUser->find('first',array(
            'contain' => array(
                'CloggyUserRole',
                'CloggyUserPermAro'
            ),
            'conditions' => array('CloggyUser.id' => $id)
        ));
    }

}