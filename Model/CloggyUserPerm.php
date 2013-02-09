<?php

class CloggyUserPerm extends CloggyAppModel {
    
    public $name = 'CloggyUserPerm';
    public $useTable = 'users_perms';
    public $belongsTo = array(
        'CloggyUserRole' => array(
            'className' => 'Cloggy.CloggyUserRole',
            'foreignKey' => 'aro_object_id'
        ),
        'CloggyUser' => array(
            'className' => 'Cloggy.CloggyUser',
            'foreignKey' => 'aro_object_id'
        )
    );
    
    /**
     * Check if aro object and aro id has been registered or not
     * @param int $roleId
     * @param string $roleObject
     * @return boolean
     */
    public function isRoleObjectIdExists($roleId,$roleObject) {
        
        $check = $this->find('count',array(
            'contain' => false,
            'conditions' => array(
                'CloggyUserPerm.aro_object_id' => $roleId,
                'CloggyUserPerm.aro_object' => $roleObject,
            )
        ));
        
        return $check < 1 ? false : true;
        
    }
    
    /**
     * Check if aro object has been registered or not
     * @param string $roleObject
     * @return boolean
     */
    public function isRoleObjectExists($roleObject) {
        
        $check = $this->find('count',array(
            'contain' => false,
            'conditions' => array('CloggyUserPerm.aro_object' => $roleObject)
        ));
        
        return $check < 1 ? false : true;
        
    }
    
    /**
     * Check if aco object need permission or not
     * @param string $acoObject
     * @param string $acoAdapter
     * @return boolean
     */
    public function isAcoObjectExists($acoObject,$acoAdapter) {
        
        $check = $this->find('count',array(
            'contain' => false,
            'conditions' => array(
                'CloggyUserPerm.aco_object' => $acoObject,
                'CloggyUserPerm.aco_adapter' => $acoAdapter,
            )
        ));
        
        return $check < 1 ? false : true;
        
    }
    
    /**
     * Check if adapter registered or not
     * @param string $adapter
     * @return boolean
     */
    public function isAdapterExists($adapter) {
        
        $check = $this->find('count',array(
            'contain' => false,
            'conditions' => array('CloggyUserPerm.aco_adapter' => $adapter)
        ));
        
        return $check < 1 ? false : true;
        
    }
    
    /**
     * Check aro has permission to aco
     * @param int $aroId
     * @param string $aroObject
     * @param string $object
     * @param string $adapter
     * @return boolean
     */
    public function isAroHasPermAco($aroId,$aroObject,$object,$adapter) {
        
        $check = $this->find('count',array(
            'contain' => false,
            'conditions' => array(
                'CloggyUserPerm.aro_object_id' => $aroId,
                'CloggyUserPerm.aro_object' => $aroObject,
                'CloggyUserPerm.aco_object' => $object,
                'CloggyUserPerm.aco_adapter' => $adapter,
            )
        ));
        
        return $check < 1 ? false : true;
        
    }
    
    /**
     * Check permission
     * 
     * @param string $object
     * @param string $adapter
     * @param int $aroId
     * @param string $aroObject
     * @param string $perms [optional]
     * @return boolean
     */
    public function checkAroPermission(
            $object,
            $adapter,
            $aroId,
            $aroObject,
            $perms='allow') {
   
        /*
         * check if registered aco registered or not
         * if not, it means all aro objects can access it
         * if registered, it means, that aco object need special permission
         */
        $checkAcoObject = $this->isAcoObjectExists($object, $adapter);        
        if($checkAcoObject) {
            
            $checkAroAco = $this->isAroHasPermAco($aroId, $aroObject, $object, $adapter);            
            
            if(!$checkAroAco) {
                return false;
            }else{
                
                /*
                 * check for single aro
                 */
               $checkPerm = $this->find('count',array(
                   'contain' => false,
                   'conditions' => array(
                       'CloggyUserPerm.aro_object_id' => $aroId,
                       'CloggyUserPerm.aro_object' => $aroObject,
                       'CloggyUserPerm.aco_object' => $object,
                       'CloggyUserPerm.aco_adapter' => $adapter,
                       'CloggyUserPerm.allow' => $perms == 'allow' ? 1 :0,
                       'CloggyUserPerm.deny' => $perms == 'deny' ? 1 :0,
                   )
               ));

               return $checkPerm < 1 ? false : true;
                
            }                        
            
        }
        
        return true;
        
    }           
    
    /**
     * Check for global permission from aco object and adapter
     * @param string $object
     * @param string $adapter
     * @param string $perm [optional]
     * @return boolean
     */
    public function isObjectPermAll($object,$adapter,$perm='allow') {
        
        $checkAllow = $this->checkAroPermission($object, $adapter, 0, '*',$perm);
        return $checkAllow;
        
    }        
    
}