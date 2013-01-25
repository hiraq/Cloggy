<?php

App::uses('AclInterface', 'Controller/Component/Acl');

class CloggyModuleAcl extends Object implements AclInterface {

    private $__Component;    

    public function check($aro, $aco, $action = "*") {
        
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