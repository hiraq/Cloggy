<?php

App::uses('CloggyAppController', 'Cloggy.Controller');
App::uses('Sanitize', 'Utility');

class CloggyUsersRoleController extends CloggyAppController {
    
    public $uses = array(
        'Cloggy.CloggyUser', 
        'Cloggy.CloggyUserRole',
        'Cloggy.CloggyValidation'
    );        

    public function beforeFilter() {

        parent::beforeFilter();        

        //load additional helpers        
        $this->helpers[] = 'Form';

        //setup pagination
        $this->paginate = array(
            'CloggyUserRole' => array(
                'limit' => 10,
                'contain' => array('CloggyUser'),
                'order' => array('role_name' => 'asc')
            )
        );
        
    }
    
    public function index() {
        
        $roles = $this->paginate('CloggyUserRole');
        
        $this->set('title_for_layout', __d('cloggy','Cloggy - Users Role Management'));
        $this->set(compact('roles'));
        
    }
    
    public function add() {
        
        if($this->request->is('post')) {
            
            /*
             * sanitize data
             */
            $this->request->data = Sanitize::clean($this->request->data, array(
                'encode' => true,
                'remove_html' => true
            ));
            
            $checkRoleExists = $this->CloggyUserRole->isRoleExists(
                    $this->request->data['CloggyUserRole']['role_name']);
            
            /*
             * validation rules
             */
            $dataValidate = $this->request->data['CloggyUserRole'];
            $this->CloggyValidation->set($dataValidate);
            $this->CloggyValidation->validate = array(
                'role_name' => array(
                    'empty' => array(
                        'rule' => 'notEmpty',
                        'required' => true,
                        'allowEmpty' => false,
                        'message' => __d('cloggy','Role name field required')
                    ),
                    'exists' => array(
                        'rule' => array('isValueEqual', $checkRoleExists, false),
                        'message' => __d('cloggy','This role name has exists.')
                    )
                )
            );
            
            /*
             * validate data
             */
            if ($this->CloggyValidation->validates()) {
                
                /*
                 * save and create new user role
                 */
                $this->CloggyUserRole->createRole(
                        $this->request->data['CloggyUserRole']['role_name']);
                
                //set notification
                $this->set('success', '<strong>' . $this->request->data['CloggyUserRole']['role_name'] . '</strong> '.__d('cloggy','has been created.'));
                
            } else {
                $this->set('errors', $this->CloggyValidation->validationErrors);
            } 
            
        }
        
        $this->set('title_for_layout', __d('cloggy','Cloggy - Role Management - Create New Role'));
    }
    
    public function edit($id = null) {
        
        if (is_null($id) || !ctype_digit($id)) {
            $this->redirect($this->referer());
            exit();
        }
        
        /*
         * detail role data
         */
        $role = $this->CloggyUserRole->find('first',array(
            'contain' => false,
            'conditions' => array('CloggyUserRole.id' => $id)
        ));
        
        /*
         * form submitted
         */
        if ($this->request->is('post')) {
            
            $dataValidate = array();
            $dataValidateRules = array();                        
            
            //requested new role
            $editedRole = $this->request->data['CloggyUserRole']['role_name'];
            
            /*
             * check if new role name exists or not
             */
            $checkRoleExists = $this->CloggyUserRole->isRoleExists(
                    $this->request->data['CloggyUserRole']['role_name']);
            
            if ($editedRole != $role['CloggyUserRole']['role_name']) {
               $dataValidate['role_name'] = $editedRole;
               $dataValidateRules['role_name'] = array(
                    'empty' => array(
                        'rule' => 'notEmpty',
                        'required' => true,
                        'allowEmpty' => false,
                        'message' => __d('cloggy','Role name field required')
                    ),
                   'exists' => array(
                        'rule' => array('isValueEqual', $checkRoleExists, false),
                        'message' => __d('cloggy','This role name has exists.')
                    )
                );
            }
            
            if(!empty($dataValidate)) {
                
                $this->CloggyValidation->set($dataValidate);
                $this->CloggyValidation->validate = $dataValidateRules;
                
                if($this->CloggyValidation->validates()) {
                    
                    $this->CloggyUserRole->id = $id;
                    $this->CloggyUserRole->save(array('CloggyUserRole' => $dataValidate));
                    
                    /*
                     * set notification and redirect
                     */
                    $this->Session->setFlash(__d('cloggy','Role name data has been updated.'), 'default', array(), 'success');
                    $this->redirect($this->referer());                                        
                    
                } else {
                    $this->set('errors', $this->CloggyValidation->validationErrors);
                }
                
            }
            
        }
        
        $this->set('title_for_layout', __d('cloggy','Cloggy - Role Management - Edit Role'));
        $this->set(compact('role', 'id'));
        
    }
    
    public function remove($id = null) {

        if (!is_null($id) && ctype_digit($id)) {
            $this->CloggyUserRole->delete($id, false);
        }

        $this->redirect($this->referer());
    }
    
}