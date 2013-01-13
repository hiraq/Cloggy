<?php

App::uses('CloggyAppController', 'Cloggy.Controller');
App::uses('Sanitize', 'Utility');

class CloggyUsersHomeController extends CloggyAppController {

  public $uses = array('Cloggy.CloggyUser', 'Cloggy.CloggyValidation');

  public function beforeFilter() {

    parent::beforeFilter();
    $this->Auth->deny('*');    

    //load additional helpers
    $this->helpers[] = 'Time';
    $this->helpers[] = 'Form';

    //setup pagination
    $this->paginate = array(
        'CloggyUser' => array(
            'limit' => 10,
            'contain' => false,
            'order' => array('user_created' => 'desc')
        )
    );

    $this->_mergePaginateSortBy();
  }

  public function index() {

    $users = $this->paginate('CloggyUser');

    $this->set('title_for_layout', 'Cloggy - Users Management');
    $this->set(compact('users'));
  }

  public function add() {

    if ($this->request->is('post')) {

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
                  'message' => 'Username field required'
              ),
              'exists' => array(
                  'rule' => array('isValueEqual', $checkUserName, false),
                  'message' => 'This username has exists.'
              )
          ),
          'user_email' => array(
              'empty' => array(
                  'rule' => 'notEmpty',
                  'required' => true,
                  'allowEmpty' => false,
                  'message' => 'Email field required'
              ),
              'email' => array(
                  'rule' => 'email',
                  'message' => 'Required valid email address'
              ),
              'exists' => array(
                  'rule' => array('isValueEqual', $checkUserEmail, false),
                  'message' => 'This email has exists.'
              )
          ),
          'user_password' => array(
              'empty' => array(
                  'rule' => 'notEmpty',
                  'required' => true,
                  'allowEmpty' => false,
                  'message' => 'Email field required'
              ),
              'equal' => array(
                  'rule' => array('equalTo', $this->request->data['CloggyUser']['user_password2']),
                  'message' => 'Not match with your password confirmation'
              )
          ),
          'user_password2' => array(
              'rule' => 'notEmpty',
              'required' => true,
              'allowEmpty' => false,
              'message' => 'Password confirmation required'
          ),
          'user_role' => array(
              'rule' => 'notEmpty',
              'required' => true,
              'allowEmpty' => false,
              'message' => 'User role required'
          )
      );

      if ($this->CloggyValidation->validates()) {

        //delete confirm password
        unset($this->request->data['CloggyUser']['user_password2']);

        /*
         * setup data
         */
        $data = $this->request->data['CloggyUser'];
        $data['user_password'] = AuthComponent::password($data['user_password']);
        $data = array_merge($data, array(
            'user_created' => date('c')
                ));

        $this->CloggyUser->create();
        $this->CloggyUser->save($data);

        $this->set('success', '<strong>' . $data['user_name'] . '</strong> has been registered.');
      } else {
        $this->set('errors', $this->CloggyValidation->validationErrors);
      }
    }

    $this->set('title_for_layout', 'Cloggy - Users Management - Add New User');
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

      if (!empty($username) && $username != $user['CloggyUser']['user_name']) {

        $checkUserName = $this->CloggyUser->isUserNameExists($dataValidate['user_name']);

        $dataValidate['user_name'] = $username;
        $dataValidateRules['user_name'] = array(
            'empty' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'allowEmpty' => false,
                'message' => 'Username field required'
            ),
            'exists' => array(
                'rule' => array('isValueEqual', $checkUserName, false),
                'message' => 'This username has exists.'
            )
        );
      }

      if (!empty($password) && AuthComponent::password($pasword) != $user['CloggyUser']['user_password']) {

        $dataValidate['user_password'] = $password;
        $dataValidateRules['user_password'] = array(
            'empty' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'allowEmpty' => false,
                'message' => 'Username field required'
            ),
            'equal' => array(
                'rule' => array('equalTo', $this->request->data['CloggyUser']['user_password2']),
                'message' => 'Not match with your password confirmation'
            )
        );
      }

      if (!empty($email) && $email != $user['CloggyUser']['user_email']) {

        $checkUserEmail = $this->CloggyUser->isUserEmailExists($dataValidate['user_email']);

        $dataValidate['user_email'] = $email;
        $dataValidateRules['user_email'] = array(
            'empty' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'allowEmpty' => false,
                'message' => 'Email field required'
            ),
            'email' => array(
                'rule' => 'email',
                'message' => 'Required valid email address'
            ),
            'exists' => array(
                'rule' => array('isValueEqual', $checkUserEmail, false),
                'message' => 'This email has exists.'
            )
        );
      }

      if (!empty($role) && $role != $user['CloggyUser']['user_role']) {

        $dataValidate['user_role'] = $role;
        $dataValidateRules['user_role'] = array(
            'empty' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'allowEmpty' => false,
                'message' => 'Email field required'
            )
        );
      }

      if (!empty($dataValidate)) {

        $this->CloggyValidation->set($dataValidate);
        $this->CloggyValidation->validate = $dataValidateRules;

        /*
         * form submit
         */
        if ($this->CloggyValidation->validates()) {

          if ($stat != $user['CloggyUser']['user_status']) {
            $dataValidate = array_merge($dataValidate, array('user_status' => $stat));
          }

          $this->CloggyUser->id = $id;
          $this->CloggyUser->save(array('CloggyUser' => $dataValidate));

          /* $this->set('success','<strong>'.$username.'</strong> data has been updated.'); */
          $this->Session->setFlash('<strong>' . $username . '</strong> data has been updated.', 'default', array(), 'success');
          $this->redirect($this->referer());
        } else {
          $this->set('errors', $this->CloggyValidation->validationErrors);
        }
      } else {

        if ($stat != $user['CloggyUser']['user_status']) {

          $this->CloggyUser->id = $id;
          $this->CloggyUser->save(array(
              'CloggyUser' => array(
                  'user_status' => $stat
              )
          ));

          /* $this->set('success','<strong>'.$username.'</strong> data has been updated.'); */
          $this->Session->setFlash('<strong>' . $username . '</strong> data has been updated.', 'default', array(), 'success');
          $this->redirect($this->referer());
        }
      }
    }

    $user = $this->CloggyUser->getUserDetail($id);
    $this->set('title_for_layout', 'Cloggy - Users Management - Edit User');
    $this->set(compact('user', 'id'));
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

  private function _mergePaginateSortBy() {

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