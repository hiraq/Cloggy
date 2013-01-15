<?php

class CloggyUserLogin extends CloggyAppModel {

    public $name = 'CloggyUserLogin';
    public $useTable = 'user_login';
    public $belongsTo = array(
        'CloggyUser' => array(
            'className' => 'Cloggy.CloggyUser',
            'foreignKey' => 'user_id'
        )
    );

    public function isLogin($id) {

        $check = $this->find('count', array(
            'contain' => false,
            'conditions' => array('CloggyUserLogin.user_id' => $id)
                ));

        return $check < 1 ? false : true;
    }

    public function removeLogin($id) {
        $this->deleteAll(array('user_id' => $id), false);
    }

    public function setLogin($id) {

        $check = $this->isLogin($id);
        if (!$check) {

            $this->create();
            $this->save(array(
                'CloggyUserLogin' => array(
                    'user_id' => $id,
                    'login_datetime' => date('c')
                )
            ));

            return $this->id;
        }

        return false;
    }

}