<?php

class DashboardController extends CloggyAppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->deny('*');
    }

    public function index() {

        $this->CloggyModuleMenu->setGroup('shortcuts', array(
            'Add New User' => cloggyUrlModule('cloggy_users', 'cloggy_users_home/add'),
            'Edit My Profile' => cloggyUrlModule('cloggy_users', 'cloggy_users_home/edit/' . $this->Auth->user('id'))
        ));

        $this->set('title_for_layout', 'Cloggy - Administration Dashboard');
    }

}