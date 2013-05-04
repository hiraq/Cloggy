<?php

App::uses('CloggyAppController', 'Cloggy.Controller');

class CloggyUsersAjaxController extends CloggyAppController {

    public $uses = array(
        'Cloggy.CloggyUser',
        'Cloggy.CloggyUserRole',
        'Cloggy.CloggyUserPerm'
    );

    public function beforeFilter() {

        parent::beforeFilter();        

        if (!$this->request->is('ajax')) {
            $this->redirect('/');
        }

        $this->autoRender = false;
        
        /*
         * disable SecurityComponent
         */
        $this->Components->disable('Security');
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
    
    public function delete_all_roles() {
        
        $roles = $this->request->data['role'];
        foreach ($roles as $role) {
            $this->CloggyUserRole->delete($role, false);
        }
        
        echo json_encode(array('msg' => 'success'));
        
    }
    
    public function delete_all_perms() {
        
        $perms = $this->request->data['perm'];
        foreach ($perms as $perm) {
            $this->CloggyUserPerm->delete($perm, false);
        }
        
        echo json_encode(array('msg' => 'success'));
        
    }

}