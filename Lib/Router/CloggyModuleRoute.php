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
        
        $registeredModules = Configure::read('Cloggy.modules');                        
        if (isset($params['name'])) {

            $moduleName = Inflector::variable($params['name']);
            $moduleName = ucfirst($moduleName);
            
            if (!in_array($moduleName, $registeredModules)) {
                return false;
            } else {

                if (!isset($params['controller']) || empty($params['controller'])) {
                    $params['controller'] = $params['name'] . '_home';
                }
                
                $params['isCloggyModule'] = 1;                
            }
        }
        
        return $params;
        
    }

}