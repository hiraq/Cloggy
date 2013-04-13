<?php

class CloggyUser extends CloggyAppModel {

    public $name = 'CloggyUser';
    public $useTable = 'users';
    public $belongsTo = array(
        'CloggyUserRole' => array(
            'className' => 'Cloggy.CloggyUserRole',
            'foreignKey' => 'users_roles_id'
        )
    );
    public $hasOne = array(
        'CloggyUserLogin' => array(
            'className' => 'Cloggy.CloggyUserLogin',
            'foreignKey' => 'user_id',
            'dependent' => false
        )
    );
    public $hasMany = array(
        'CloggyNode' => array(
            'className' => 'Cloggy.CloggyNode',
            'foreignKey' => 'user_id',
            'dependent' => false
        ),
        'CloggyUserMeta' => array(
            'className' => 'Cloggy.CloggyUserMeta',
            'foreignKey' => 'user_id',
            'dependent' => false
        ),
        'CloggyNodeType' => array(
            'className' => 'Cloggy.CloggyNodeType',
            'foreignKey' => 'user_id',
            'dependent' => false
        ),
        'CloggyUserPermAro' => array(
            'className' => 'Cloggy.CloggyUserPerm',
            'foreignKey' => 'aro_object_id',
            'dependent' => false
        )
    );
    
    public function beforeSave($options = array()) {
        
        if (isset($this->data['CloggyUser']['user_password']) 
                && !empty($this->data['CloggyUser']['user_password'])) {
            
            $this->data['CloggyUser']['user_password'] = AuthComponent::password($this->data['CloggyUser']['user_password']);
        }
        
        return true;
    }

    /**
     * Check if name exists or not
     * @param string $name
     * @return boolean
     */
    public function isUserNameExists($name) {

        $check = $this->find('count', array(
            'contain' => false,
            'conditions' => array('CloggyUser.user_name' => $name)
                ));

        return $check < 1 ? false : true;
    }

    /**
     * Check if email exists or not
     * @param string $email
     * @return boolean
     */
    public function isUserEmailExists($email) {

        $check = $this->find('count', array(
            'contain' => false,
            'conditions' => array('CloggyUser.user_email' => $email)
                ));

        return $check < 1 ? false : true;
    }

    /**
     * Setup user login
     * @param int $id
     */
    public function setUserLastLogin($id) {

        $this->id = $id;
        $this->save(array(
            'CloggyUser' => array(
                'user_last_login' => date('c')
            )
        ));
    }

    /**
     * Get all user data by user id
     * @param int $id
     * @return array
     */
    public function getUserDetail($id) {
        return $this->find('first', array(
            'contain' => array('CloggyUserRole'),
            'conditions' => array(
                'CloggyUser.id' => $id
            )
        ));
    }

    /**
     * Get user status by user id
     * 
     * @param int $id
     * @return array
     */
    public function getUserStatus($id) {

        $data = $this->find('first', array(
            'contain' => false,
            'conditions' => array('CloggyUser.id' => $id),
            'fields' => array('CloggyUser.user_status')
        ));

        return $data;
    }

    /**
     * Get user last login data by user id
     * 
     * @param int $id
     * @return array
     */
    public function getUserLastLogin($id) {

        $data = $this->find('first', array(
            'contain' => false,
            'conditions' => array('CloggyUser.id' => $id),
            'fields' => array('CloggyUser.user_last_login')
        ));

        return $data;
    }

    /**
     * Get user role by user id
     * @param int $id
     * @return boolean
     */
    public function getUserRole($id) {

        $data = $this->find('first', array(
            'contain' => array('CloggyUserRole'),
            'conditions' => array('CloggyUser.id' => $id)
        ));
        
        if (!empty($data)) {
            return $data['CloggyUserRole']['role_name'];
        }
        
        return false;
    }

}