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
        $this->Auth->deny('*');

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
        
        $this->set('title_for_layout', 'Cloggy - Users Permission Management');
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
                        'message' => 'Adapter name field required'
                    ),
                    'comparison' => array(
                        'rule' => array('inList',array('module','model','url')),
                        'required' => true,
                        'allowEmpty' => false,
                        'message' => 'You must choose adapter.'
                    )
                ),
                'role_id' => array(
                    'rule' => array('isValueEqual', $checkPermExists, false),
                    'required' => true,
                    'allowEmpty' => false,
                    'message' => 'This role and permission has been exists.'
                ),
                'aco_object' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'allowEmpty' => false,
                    'message' => 'Object field required'
                )
            );
            
            /*
             * validate data
             */
            if ($this->CloggyValidation->validates()) {
                
                $this->CloggyUserPerm->create();
                $this->CloggyUserPerm->save($dataToSave);
                
                $this->set('success','Permission has been setup');
                
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
        
        $this->set('title_for_layout', 'Cloggy - Users Permission Management - Setup Permission');
        $this->set(compact('roles'));
        
    }
    
}