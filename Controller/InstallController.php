<?php

App::uses('Sanitize', 'Utility');

class InstallController extends CloggyAppController {

    public $uses = array(
        'Cloggy.CloggyUser', 
        'Cloggy.CloggyUserRole',
        'Cloggy.CloggyValidation'
    );    

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('*');
    }

    public function index() {

        $users = $this->CloggyUser->find('count', array(
            'contain' => false
        ));

        if ($users < 1) {

            /*
             * form submitted & processing data
             */
            if ($this->request->is('post')) {

                /*
                 * sanitize user input
                 */
                $this->request->data = Sanitize::clean($this->request->data, array(
                            'encode' => true,
                            'remove_html' => true
                        ));

                $dataValidate = $this->request->data['CloggyUser'];

                /*
                 * validation rules
                 */
                $this->CloggyValidation->set($dataValidate);
                $this->CloggyValidation->validate = array(
                    'user_name' => array(
                        'rule' => 'notEmpty',
                        'required' => true,
                        'allowEmpty' => false,
                        'message' => __d('cloggy','Username field cannot be empty')
                    ),
                    'user_password' => array(
                        'rule' => 'notEmpty',
                        'required' => true,
                        'allowEmpty' => false,
                        'message' => __d('cloggy','Password field cannot be empty')
                    ),
                    'user_email' => array(
                        'rule' => 'email',
                        'required' => true,
                        'allowEmpty' => false,
                        'message' => __d('cloggy','Valid address required')
                    )
                );

                if ($this->CloggyValidation->validates()) {
                    
                    //setup roles
                    $roleId = $this->CloggyUserRole->createRole('super administrator');

                    /*
                     * setup user data
                     */                    
                    $this->request->data['CloggyUser'] = array_merge($this->request->data['CloggyUser'], array(
                        'user_last_login' => date('c'),
                        'users_roles_id' => $roleId,
                        'user_created' => date('c'),
                        'user_status' => 1
                            ));

                    /*
                     * save new user
                     */
                    $this->CloggyUser->create();
                    $this->CloggyUser->save($this->request->data);

                    /*
                     * install success
                     */
                    $this->Session->setFlash(__d('cloggy','Your account has been activated.'), 'default', array(), 'install_success');
                    $this->redirect($this->_base . '/login');
                } else {
                    $this->set('errors', $this->CloggyValidation->validationErrors);
                }
            }//end processing form
            //page title
            $this->set('title_for_layout', __d('cloggy','Cloggy - Setup User'));
        }
    }

}