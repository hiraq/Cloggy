<?php

App::uses('CloggyAppController', 'Cloggy.Controller');

class CloggyUsersAjaxController extends CloggyAppController {

  public $uses = array('Cloggy.CloggyUser');

  public function beforeFilter() {

    parent::beforeFilter();
    $this->Auth->deny('*');

    if (!$this->request->is('ajax')) {
      $this->redirect('/');
    }

    $this->autoRender = false;
  }

  public function delete_all() {

    $users = $this->request->data['user'];
    foreach ($users as $user) {
      $this->CloggyUser->delete($user, false);
    }

    echo json_encode(array('msg' => 'success'));
  }

  public function disable_all() {

    $users = $this->request->data['user'];
    foreach ($users as $user) {

      $this->CloggyUser->id = $user;
      $this->CloggyUser->save(array(
          'CloggyUser' => array(
              'user_status' => 0
          )
      ));
    }

    echo json_encode(array('msg' => 'success'));
  }

  public function enable_all() {

    $users = $this->request->data['user'];
    foreach ($users as $user) {

      $this->CloggyUser->id = $user;
      $this->CloggyUser->save(array(
          'CloggyUser' => array(
              'user_status' => 1
          )
      ));
    }

    echo json_encode(array('msg' => 'success'));
  }

}