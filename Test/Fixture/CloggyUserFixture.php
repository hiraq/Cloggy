<?php

class CloggyUserFixture extends CakeTestFixture {

    public $useDbConfig = 'test';
    public $import = array('model' => 'Cloggy.CloggyUser');

    public function init() {

        $this->records = array(
            array(
                'id' => 1,
                'users_roles_id' => 1,
                'user_name' => 'test user',
                'user_password' => 'test',
                'user_email' => 'test@test.com',                
                'user_status' => 1,
                'user_last_login' => date('c'),
                'user_created' => date('c')
            ),
            array(
                'id' => 2,
                'users_roles_id' => 2,
                'user_name' => 'test user 2',
                'user_password' => 'test2',
                'user_email' => 'test2@test.com',                
                'user_status' => 0,
                'user_last_login' => date('c'),
                'user_created' => date('c')
            )
        );
        parent::init();
    }

}