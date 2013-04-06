<?php

App::uses('CloggyAppController', 'Cloggy.Controller');
App::uses('Sanitize', 'Utility');

class CloggyUsersHomeController extends CloggyAppController {

    public $uses = array(
        'Cloggy.CloggyUser', 
        'Cloggy.CloggyUserRole',
        'Cloggy.CloggyValidation'
    );

    public function beforeFilter() {

        parent::beforeFilter();        

        //load additional helpers
        $this->helpers[] = 'Time';
        $this->helpers[] = 'Form';

        //setup pagination
        $this->paginate = array(
            'CloggyUser' => array(
                'limit' => 10,
                'contain' => array('CloggyUserRole'),
                'order' => array('user_created' => 'desc')
            )
        );

        $this->__mergePaginateSortBy();
    }

    public function index() {

        $users = $this->paginate('CloggyUser');
        
        $this->set('title_for_layout', __d('cloggy','Cloggy - Users Management'));
        $this->set(compact('users'));
    }

    public function add() {

        if ($this->request->is('post')) {

            /*
             * sanitize data
             */
            $this->request->data = Sanitize::clean($this->request->data, array(
                'encode' => true,
                'remove_html' => true
            ));

            $dataValidate = $this->request->data['CloggyUser'];

            //not validate		
            unset($dataValidate['user_status']);

            $checkUserName = $this->CloggyUser->isUserNameExists($dataValidate['user_name']);
            $checkUserEmail = $this->CloggyUser->isUserEmailExists($dataValidate['user_email']);

            $this->CloggyValidation->set($dataValidate);
            $this->CloggyValidation->validate = array(
                'user_name' => array(
                    'empty' => array(
                        'rule' => 'notEmpty',
                        'required' => true,
                        'allowEmpty' => false,
                        'message' => __d('cloggy','Username field required')
                    ),
                    'exists' => array(
                        'rule' => array('isValueEqual', $checkUserName, false),
                        'message' => __d('cloggy','This username has exists.')
                    )
                ),
                'user_email' => array(
                    'empty' => array(
                        'rule' => 'notEmpty',
                        'required' => true,
                        'allowEmpty' => false,
                        'message' => __d('cloggy','Email field required')
                    ),
                    'email' => array(
                        'rule' => 'email',
                        'message' => __d('cloggy','Required valid email address')
                    ),
                    'exists' => array(
                        'rule' => array('isValueEqual', $checkUserEmail, false),
                        'message' => __d('cloggy','This email has exists.')
                    )
                ),
                'user_password' => array(
                    'empty' => array(
                        'rule' => 'notEmpty',
                        'required' => true,
                        'allowEmpty' => false,
                        'message' => __d('cloggy','Email field required')
                    ),
                    'equal' => array(
                        'rule' => array('equalTo', $this->request->data['CloggyUser']['user_password2']),
                        'message' => __d('cloggy','Not match with your password confirmation')
                    )
                ),
                'user_password2' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'allowEmpty' => false,
                    'message' => __d('cloggy','Password confirmation required')
                ),
                'user_role' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'allowEmpty' => false,
                    'message' => __d('cloggy','User role required')
                )
            );

            if ($this->CloggyValidation->validates()) {

                //delete confirm password
                unset($this->request->data['CloggyUser']['user_password2']);
                
                //delete user role
                $roleId = $this->request->data['CloggyUser']['user_role'];
                unset($this->request->data['CloggyUser']['user_role']);
                
                /*
                 * setup data
                 */
                $data = $this->request->data['CloggyUser'];
                $data['user_password'] = AuthComponent::password($data['user_password']);
                $data['users_roles_id'] = $roleId;                
                $data = array_merge($data, array(
                    'user_created' => date('c')
                ));

                $this->CloggyUser->create();
                $this->CloggyUser->save($data);

                $this->set('success', '<strong>' . $data['user_name'] . '</strong> '.__d('cloggy','has been registered.'));
            } else {
                $this->set('errors', $this->CloggyValidation->validationErrors);
            }
        }

        /*
         * list of roles
         */
        $roles = $this->CloggyUserRole->find('list',array(
            'contain' => false,            
            'fields' => array('CloggyUserRole.id','CloggyUserRole.role_name')
        ));
        
        $this->set('title_for_layout', __d('cloggy','Cloggy - Users Management - Add New User'));
        $this->set(compact('roles'));
        
    }

    public function edit($id = null) {

        if (is_null($id) || !ctype_digit($id)) {
            $this->redirect($this->referer());
            exit();
        }        
        
        $user = $this->CloggyUser->getUserDetail($id);        

        /*
         * unknown user
         */
        if (empty($user)) {
            $this->redirect($this->referer());
            exit();
        }
        
        $roles = $this->CloggyUserRole->find('list',array(
            'contain' => false,            
            'fields' => array('CloggyUserRole.id','CloggyUserRole.role_name')
        ));
        
        /*
         * form submitted
         */
        if ($this->request->is('post')) {

            $dataValidate = array();
            $dataValidateRules = array();

            $username = $this->request->data['CloggyUser']['user_name'];
            $password = $this->request->data['CloggyUser']['user_password'];
            $email = $this->request->data['CloggyUser']['user_email'];
            $role = $this->request->data['CloggyUser']['user_role'];
            $stat = $this->request->data['CloggyUser']['user_status'];

            /*
             * if user name need to updated
             */
            if (!empty($username) && $username != $user['CloggyUser']['user_name']) {

                $checkUserName = $this->CloggyUser->isUserNameExists($dataValidate['user_name']);

                $dataValidate['user_name'] = $username;
                $dataValidateRules['user_name'] = array(
                    'empty' => array(
                        'rule' => 'notEmpty',
                        'required' => true,
                        'allowEmpty' => false,
                        'message' => __d('cloggy','Username field required')
                    ),
                    'exists' => array(
                        'rule' => array('isValueEqual', $checkUserName, false),
                        'message' => __d('cloggy','This username has exists.')
                    )
                );
            }

            /*
             * if password need to updated
             */
            if (!empty($password) && AuthComponent::password($pasword) != $user['CloggyUser']['user_password']) {

                $dataValidate['user_password'] = $password;
                $dataValidateRules['user_password'] = array(
                    'empty' => array(
                        'rule' => 'notEmpty',
                        'required' => true,
                        'allowEmpty' => false,
                        'message' => __d('cloggy','Username field required')
                    ),
                    'equal' => array(
                        'rule' => array('equalTo', $this->request->data['CloggyUser']['user_password2']),
                        'message' => __d('cloggy','Not match with your password confirmation')
                    )
                );
            }

            /**
             * if email need to updated
             */
            if (!empty($email) && $email != $user['CloggyUser']['user_email']) {

                $checkUserEmail = $this->CloggyUser->isUserEmailExists($dataValidate['user_email']);

                $dataValidate['user_email'] = $email;
                $dataValidateRules['user_email'] = array(
                    'empty' => array(
                        'rule' => 'notEmpty',
                        'required' => true,
                        'allowEmpty' => false,
                        'message' => __d('cloggy','Email field required')
                    ),
                    'email' => array(
                        'rule' => 'email',
                        'message' => __d('cloggy','Required valid email address')
                    ),
                    'exists' => array(
                        'rule' => array('isValueEqual', $checkUserEmail, false),
                        'message' => __d('cloggy','This email has exists.')
                    )
                );
            }

            /*
             * if role need to updated
             */
            if (!empty($role) && $role != $user['CloggyUserRole']['id']) {

                $dataValidate['users_roles_id'] = $role;
                $dataValidateRules['users_roles_id'] = array(
                    'empty' => array(
                        'rule' => 'notEmpty',
                        'required' => true,
                        'allowEmpty' => false,
                        'message' => __d('cloggy','User role field required')
                    )
                );
            }
            
            /*
             * if user status need to updated
             */
            if ($stat != $user['CloggyUser']['user_status']) {
                $dataValidate['user_status'] = $stat;
                $dataValidateRules['user_status'] = array(
                    'empty' => array(
                        'rule' => 'notEmpty',
                        'required' => true,
                        'allowEmpty' => false,
                        'message' => __d('cloggy','User status field required')
                    )
                );
            }

            /*
             * if need to validate
             */
            if (!empty($dataValidate)) {

                /*
                 * setup validation
                 */
                $this->CloggyValidation->set($dataValidate);
                $this->CloggyValidation->validate = $dataValidateRules;

                /*
                 * form submit
                 */
                if ($this->CloggyValidation->validates()) {                                                         

                    $this->CloggyUser->id = $id;
                    $this->CloggyUser->save(array('CloggyUser' => $dataValidate));

                    /* $this->set('success','<strong>'.$username.'</strong> data has been updated.'); */
                    $this->Session->setFlash('<strong>' . $username . '</strong> '.__d('cloggy','data has been updated.'), 'default', array(), 'success');
                    $this->redirect($this->referer());
                    
                } else {
                    $this->set('errors', $this->CloggyValidation->validationErrors);
                }
            }
        }

        $user = $this->CloggyUser->getUserDetail($id);        
        $this->set('title_for_layout', __d('cloggy','Cloggy - Users Management - Edit User'));
        $this->set(compact('user', 'id','roles'));
    }

    public function remove($id = null) {

        if (!is_null($id) && ctype_digit($id)) {
            $this->CloggyUser->delete($id, false);
        }

        $this->redirect($this->referer());
    }

    public function disable($id = null) {

        if (!is_null($id) && ctype_digit($id)) {
            $this->CloggyUser->id = $id;
            $this->CloggyUser->save(array(
                'CloggyUser' => array(
                    'user_status' => 0
                )
            ));
        }

        $this->redirect($this->referer());
    }

    public function enable($id = null) {

        if (!is_null($id) && ctype_digit($id)) {
            $this->CloggyUser->id = $id;
            $this->CloggyUser->save(array(
                'CloggyUser' => array(
                    'user_status' => 1
                )
            ));
        }

        $this->redirect($this->referer());
    }

    private function __mergePaginateSortBy() {

        if (isset($this->request->params['named']['sort_index'])
                && !empty($this->request->params['named']['sort_index'])) {

            $this->paginate = array();
            $paginateRules = array();

            switch ($this->request->params['named']['sort_index']) {

                case 'status_asc':
                    $paginateRules = array(
                        'order' => array('CloggyUser.user_status' => 'asc')
                    );
                    break;

                case 'status_desc':
                    $paginateRules = array(
                        'order' => array('CloggyUser.user_status' => 'desc')
                    );
                    break;

                case 'role_asc':
                    $paginateRules = array(
                        'order' => array('CloggyUser.user_role' => 'asc')
                    );
                    break;

                case 'role_desc':
                    $paginateRules = array(
                        'order' => array('CloggyUser.user_role' => 'desc')
                    );
                    break;

                case 'name_asc':
                    $paginateRules = array(
                        'order' => array('CloggyUser.user_name' => 'asc')
                    );
                    break;

                case 'name_desc':
                    $paginateRules = array(
                        'order' => array('CloggyUser.user_name' => 'desc')
                    );
                    break;
            }

            $this->Paginator->settings = array(
                'CloggyUser' => array_merge($paginateRules, array(
                    'limit' => 10,
                    'contain' => false
                ))
            );
        }
    }

}