<?php

App::uses('CloggyAppController', 'Cloggy.Controller');
App::uses('Sanitize', 'Utility');

class CloggyUsersPermController extends CloggyAppController {
    
    public $uses = array(
        'Cloggy.CloggyUser', 
        'Cloggy.CloggyUserPerm',
        'Cloggy.CloggyUserRole',
        'Cloggy.CloggyValidation'
    );        

    public function beforeFilter() {

        parent::beforeFilter();        

        //load additional helpers        
        $this->helpers[] = 'Form';

        //setup pagination
        $this->paginate = array(
            'CloggyUserPerm' => array(
                'limit' => 10,
                'contain' => array('CloggyUserRole')                
            )
        );
        
    }
    
    public function index() {
        
        $perms = $this->paginate('CloggyUserPerm');
        
        $this->set('title_for_layout', __d('cloggy','Cloggy - Users Permission Management'));
        $this->set(compact('perms'));
        
    }
    
    public function create() {
        
        /*
         * form submitted
         */
        if ($this->request->is('post')) {
            
            $dataToSave = array();
            
            /*
             * sanitize data
             */
            $this->request->data = Sanitize::clean($this->request->data, array(
                'encode' => true,
                'remove_html' => true
            ));
            
            /*
             * permission data 
             */
            $dataToSave['CloggyUserPerm']['aro_object_id'] = $this->request->data['CloggyUserPerm']['role_id'];            
            $dataToSave['CloggyUserPerm']['aro_object'] = ($this->request->data['CloggyUserPerm']['role_id'] == 0) ? '*' : 'roles';
            $dataToSave['CloggyUserPerm']['aco_object'] = $this->request->data['CloggyUserPerm']['aco_object'];
            $dataToSave['CloggyUserPerm']['aco_adapter'] = $this->request->data['CloggyUserPerm']['aco_adapter'];
            
            /*
             * permission value type(allow/deny)
             */
            $perm = $this->request->data['CloggyUserPerm']['perm'];
            switch($perm) {
                
                case 0:
                    $dataToSave['CloggyUserPerm']['allow'] = 0;
                    $dataToSave['CloggyUserPerm']['deny'] = 1;
                    break;
                
                default:
                    $dataToSave['CloggyUserPerm']['allow'] = 1;
                    $dataToSave['CloggyUserPerm']['deny'] = 0;
                    break;
                
            }
            
            /*
             * check if aro has permission to aco
             */
            $checkPermExists = $this->CloggyUserPerm->isAroHasPermAco(
                $dataToSave['CloggyUserPerm']['aro_object_id'],
                $dataToSave['CloggyUserPerm']['aro_object'],
                $dataToSave['CloggyUserPerm']['aco_object'],
                $dataToSave['CloggyUserPerm']['aco_adapter']
            );
            
            /*
             * prepare validation
             */
            $this->CloggyValidation->set($this->request->data['CloggyUserPerm']);
            $this->CloggyValidation->validate = array(
                'aco_adapter' => array(
                    'empty' => array(
                        'rule' => 'notEmpty',
                        'required' => true,
                        'allowEmpty' => false,
                        'message' => __d('cloggy','Adapter name field required')
                    ),
                    'comparison' => array(
                        'rule' => array('inList',array('module','model','url')),
                        'required' => true,
                        'allowEmpty' => false,
                        'message' => __d('cloggy','You must choose adapter.')
                    )
                ),
                'role_id' => array(
                    'rule' => array('isValueEqual', $checkPermExists, false),
                    'required' => true,
                    'allowEmpty' => false,
                    'message' => __d('cloggy','This role and permission has been exists.')
                ),
                'aco_object' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'allowEmpty' => false,
                    'message' => __d('cloggy','Object field required')
                )
            );
            
            /*
             * validate data
             */
            if ($this->CloggyValidation->validates()) {
                
                $this->CloggyUserPerm->create();
                $this->CloggyUserPerm->save($dataToSave);
                
                $this->set('success',__d('cloggy','Permission has been setup'));
                
            } else {
                $this->set('errors', $this->CloggyValidation->validationErrors);
            }
            
        }
        
        /*
         * get roles
         */
        $roles = $this->CloggyUserRole->find('list',array(
            'contain' => false,
            'fields' => array('CloggyUserRole.id','CloggyUserRole.role_name'),
            'order' => array('CloggyUserRole.role_name' => 'asc')
        ));
        
        $this->set('title_for_layout', __d('cloggy','Cloggy - Users Permission Management - Setup Permission'));
        $this->set(compact('roles'));
        
    }
    
    public function edit($id=null) {
        
        if (is_null($id) || !ctype_digit($id)) {
            $this->redirect($this->referer());
            exit();
        }
        
        /*
         * get detail permission
         */
        $perm = $this->CloggyUserPerm->find('first',array(
            'contain' => array('CloggyUserRole'),
            'conditions' => array('CloggyUserPerm.id' => $id)
        ));
        
        /*
         * unknown permission
         */
        if (empty($perm)) {
            $this->redirect($this->referer());
            exit();
        }
        
        /*
         * form submitted
         */
        if ($this->request->is('post')) {
            
            $dataValidate = array();
            $dataValidateRules = array();                        
            
            /*
             * single data
             */
            $aroId = $this->request->data['CloggyUserPerm']['role_id'];            
            $aroObject = ($this->request->data['CloggyUserPerm']['role_id'] == 0) ? '*' : 'roles';
            $acoObject = $this->request->data['CloggyUserPerm']['aco_object'];
            $acoAdapter = $this->request->data['CloggyUserPerm']['aco_adapter'];
            
            /*
             * check if aro has permission to aco
             */
            $checkPermExists = $this->CloggyUserPerm->isAroHasPermAco(
                $aroId,
                $aroObject,
                $acoObject,
                $acoAdapter
            );
            
            /*
             * aro id > role_id
             */
            if ($aroId != $perm['CloggyUserPerm']['aro_object_id']) {
                $dataValidate['aro_object_id'] = $aroId;
                $dataValidateRules['aro_object_id'] = array(
                    'rule' => array('isValueEqual', $checkPermExists, false),
                    'required' => true,
                    'allowEmpty' => false,
                    'message' => __d('cloggy','This role and permission has been exists.')
                );
            }
            
            /*
             * requested new aco object
             */
            if ($acoObject != $perm['CloggyUserPerm']['aco_object']) {
                $dataValidate['aco_object'] = $acoObject;
                $dataValidateRules['aco_object'] = array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'allowEmpty' => false,
                    'message' => __d('cloggy','Object field required')
                );
            }
            
            /*
             * aco adapter
             */
            if ($acoAdapter != $perm['CloggyUserPerm']['aco_adapter']) {
                $dataValidate['aco_adapter'] = $acoAdapter;
                $dataValidateRules['aco_adapter'] = array(
                    'rule' => array('inList',array('module','model','url')),
                    'required' => true,
                    'allowEmpty' => false,
                    'message' => __d('cloggy','You must choose adapter.')
                );
            }
            
            /*
             * perm access
             */
            switch($this->request->data['CloggyUserPerm']['perm']) {
                
                case 0:
                    $dataValidate['allow'] = 0;
                    $dataValidate['deny'] = 1;                        
                    break;

                default:
                    $dataValidate['allow'] = 1;
                    $dataValidate['deny'] = 0;
                    break;

            }
            
            $dataValidate['perm'] = $this->request->data['CloggyUserPerm']['perm'];
            
            /*
             * setup data perm access
             */
            $dataValidateRules['perm'] = array(
                'rule' => 'notEmpty',
                'required' => true,
                'allowEmpty' => false,
                'message' => __d('cloggy','Permission field required')
            );
            
            if (!empty($dataValidate)) {
                
                $this->CloggyValidation->set($dataValidate);
                $this->CloggyValidation->validate = $dataValidateRules;
                
                if ($this->CloggyValidation->validates()) {                                       
                    
                    $dataValidate['aro_object'] = $aroObject;
                    
                    /*
                     * update data
                     */
                    $this->CloggyUserPerm->id = $id;
                    $this->CloggyUserPerm->save(array(
                        'CloggyUserPerm' => $dataValidate
                    ));
                    
                     /*
                      * set notification and redirect
                      */
                    $this->Session->setFlash(__d('cloggy','Permission data has been updated.'), 'default', array(), 'success');
                    $this->redirect($this->referer());                          
                    
                }
                
            }
            
        }
        
        /*
         * get roles
         */
        $roles = $this->CloggyUserRole->find('list',array(
            'contain' => false,
            'fields' => array('CloggyUserRole.id','CloggyUserRole.role_name'),
            'order' => array('CloggyUserRole.role_name' => 'asc')
        ));
        
        $this->set('title_for_layout', __d('cloggy','Cloggy - Users Permission Management - Edit Permission'));
        $this->set(compact('roles','id','perm'));
        
    }
    
    public function remove($id = null) {

        if (!is_null($id) && ctype_digit($id)) {
            $this->CloggyUserPerm->delete($id, false);
        }

        $this->redirect($this->referer());
    }
    
}