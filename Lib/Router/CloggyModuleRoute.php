<?php

class CloggyModuleRoute extends CakeRoute {

    public function parse($url) {

        /*
         * parse url
         */
        $params = parent::parse($url);
        if (!$params || empty($params)) {            
            return false;
        }
        
        //get all registered modules
        $registeredModules = Configure::read('Cloggy.modules');                        
        
        /*
         * only active at module request
         */
        if (isset($params['name'])) {

            /*
             * setup module name
             */
            $moduleName = Inflector::variable($params['name']);
            $moduleName = ucfirst($moduleName);
            
            /*
             * check if requested module listed on registered modules
             */
            if (!in_array($moduleName, $registeredModules)) {
                return false;
            } else {

                /*
                 * if empty controller it means go to module home controller
                 */
                if (!isset($params['controller']) || empty($params['controller'])) {
                    $params['controller'] = $params['name'] . '_home';
                }
                
                //set flag that current request is module base
                $params['isCloggyModule'] = 1;                
            }
        }
        
        return $params;
        
    }

}