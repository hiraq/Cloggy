<?php

class CloggyUserRoleFixture extends CakeTestFixture {

    public $useDbConfig = 'test';
    public $import = array('model' => 'Cloggy.CloggyUserRole');

    public function init() {

        $this->records = array(
            array(
                'id' => 1,
                'role_name' => 'admin'
            ),
            array(
                'id' => 2,
                'role_name' => 'test'
            )
        );

        parent::init();
    }

}