<?php

App::uses('AclInterface', 'Controller/Component/Acl');

class CloggyModuleAcl extends Object implements AclInterface {

    private $__Component;    

    /**
     * Check if aro has permission to access aco
     * @param string $aro
     * @param string $aco
     * @param string $action [optional]
     * @return boolean
     */
    public function check($aro, $aco, $action = "*") {
        
        $Rule = $this->__Component->getRuleObject();
        $rules = $Rule->getRulesByAcoAdapter($aco,'module');
        
        if (!empty($rules)) {
            
            $aroId = $aro['id'];
            $aroType = $aro['type'];
            
            $allowed = false;
            $aroIds = array();
            
            foreach($rules as $rule) {

                $aroIds[] = $rule['CloggyUserPerm']['aro_object_id'];

                /*
                check if requester aro has connection with requested aco
                 */
                if (!in_array($aroId,$aroIds)) {
                    $allowed = true;
                } else {
                    if($rule['CloggyUserPerm']['aro_object_id'] == $aroId 
                        && $rule['CloggyUserPerm']['aro_object'] == $aroType
                        && $rule['CloggyUserPerm']['aco_object'] == $aco) {
                    
                        $allowed = $rule['CloggyUserPerm']['allow'] == 1 ? true : false;
                        
                    }
                }                            
                
            }
            
            return $allowed;
            
        }
        
        return true;
        
    }

    public function allow($aro, $aco, $action = "*") {
        return false;
    }

    public function deny($aro, $aco, $action = "*") {
        return false;
    }

    public function inherit($aro, $aco, $action = "*") {
        return false;
    }

    public function initialize(Component $component) {
        $this->__Component = $component;
    }    

}