<?php

App::uses('AclInterface', 'Controller/Component/Acl');

class CloggyModuleAcl extends Object implements AclInterface {

    private $__Component;
    private $__rules;

    public function isActionForbidden($action) {
        if (isset($this->__rules)
                && !empty($this->__rules)) {

            $return = false;
            foreach ($this->__rules as $role => $rules) {

                foreach ($rules as $rule) {

                    if ($rule == $action) {
                        $return = true;
                        break;
                    }
                }
            }

            return $return;
        } else {
            return false;
        }
    }

    public function isRoleListed($role) {

        if (isset($this->__rules) && !empty($this->__rules) && is_array($this->__rules)) {
            return array_key_exists($role, $this->__rules);
        }
    }

    public function check($aro, $aco, $action = "*") {

        /*
         * check user role listed or not
         */
        $checkAro = $this->isRoleListed($aro);

        /*
         * check action listed or not
         */
        $checkAction = $this->isActionForbidden($action);

        if ($checkAction) {

            if ($checkAro) {

                $return = false;
                foreach ($this->__rules as $role => $rules) {

                    if ($role == $aro) {

                        foreach ($rules as $rule) {
                            if ($rule == $action) {
                                $return = true;
                                break;
                            }
                        }
                    }
                }

                return $return;
            } else {
                return false;
            }
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
        return false;
    }

    public function setRules($rules) {
        $this->__rules = $rules;
    }

}