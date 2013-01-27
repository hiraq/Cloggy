<?php

class CloggyUserPermFixture extends CakeTestFixture {

    public $useDbConfig = 'test';
    public $import = array('model' => 'Cloggy.CloggyUserPerm');

    public function init() {

        $this->records = array(
            array(
                'id' => 1,
                'aro_object_id' => 1,
                'aro_object' => 'roles',
                'aco_object' => 'controller/action',
                'aco_adapter' => 'controller',
                'allow' => 1,
                'deny' => 0
            ),
            array(
                'id' => 2,
                'aro_object_id' => 1,
                'aro_object' => 'users',
                'aco_object' => 'controller/action',
                'aco_adapter' => 'controller',
                'allow' => 0,
                'deny' => 1
            ),
            array(
                'id' => 3,
                'aro_object_id' => 0,
                'aro_object' => '*',
                'aco_object' => 'controller/action',
                'aco_adapter' => 'controller',
                'allow' => 1,
                'deny' => 0
            ),
            array(
                'id' => 4,
                'aro_object_id' => 0,
                'aro_object' => '*',
                'aco_object' => 'controller3/action3',
                'aco_adapter' => 'controller',
                'allow' => 0,
                'deny' => 1
            ),
            array(
                'id' => 5,
                'aro_object_id' => 1,
                'aro_object' => 'users',
                'aco_object' => 'controller3/action3',
                'aco_adapter' => 'controller',
                'allow' => 1,
                'deny' => 0
            ),
            array(
                'id' => 6,
                'aro_object_id' => 1,
                'aro_object' => 'roles',
                'aco_object' => 'TestModule',
                'aco_adapter' => 'module',
                'allow' => 0,
                'deny' => 1
            ),
            array(
                'id' => 7,
                'aro_object_id' => 1,
                'aro_object' => 'roles',
                'aco_object' => 'TestModule2',
                'aco_adapter' => 'module',
                'allow' => 1,
                'deny' => 0
            )
        );

        parent::init();
    }

}