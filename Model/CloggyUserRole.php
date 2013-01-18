<?php

class CloggyUserRole extends CloggyAppModel {
    
    public $name = 'CloggyUserRole';
    public $useTable = 'users_roles';
    public $hasMany = array(
        'CloggyUser' => array(
            'className' => 'Cloggy.CloggyUser',
            'foreignKey' => 'users_roles_id',
            'dependent' => false
        ),
        'CloggyRolePermAro' => array(
            'className' => 'Cloggy.CloggyUserPerm',
            'foreignKey' => 'aro_object_id',
            'dependent' => false
        )
    );
    
    public function isRoleExists($role) {
        
        $check = $this->find('count',array(
            'contain' => false,
            'conditions' => array('CloggyUserRole.role_name' => $role)
        ));
        
        return $check < 1 ? false : true;
        
    }
    
    public function createRole($role) {
        
        $check = $this->isRoleExists($role);
        if(!$check) {
            
            $this->create();
            $this->save(array(
                'CloggyUserRole' => array(
                    'role_name' => $role
                )
            ));
            
            return $this->id;
            
        }
        
        return false;
        
    }
    
    public function updateRole($id,$newRole) {
        
        $this->id = $id;
        $this->save(array(
            'CloggyUserRole' => array(
                'role_name' => $newRole
            )
        ));
        
    }
    
}