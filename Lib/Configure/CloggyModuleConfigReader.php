<?php

class CloggyModuleConfigReader implements ConfigReaderInterface {

    /**
     * Module requested path
     * @var string 
     */
    private $__path;
    private $__moduleName;

    /**
     * Set module requested path
     * @param string $moduleName
     */
    public function __construct($moduleName) {
        $this->__path = APP . 'Plugin' . DS . 'Cloggy' . DS . 'Module' . DS . $moduleName . DS . 'Config' . DS;
        $this->__moduleName = $moduleName;
    }

    /**
     * Load requested config file
     * @param string $key
     * @return boolean
     */
    public function read($key) {

        if (is_dir($this->__path)) {

            $filepath = $this->__path . $key . '.php';
            
            if (file_exists($filepath)) {                
                require_once $filepath;
                return Configure::read('Cloggy.' . $this->__moduleName . '.' . $key);
            }

            return array();
        }

        return array();
    }

    /**
     * We're not using this function
     * 
     * @param type $key
     * @param type $data
     * @return boolean
     */
    public function dump($key, $data) {
        return false;
    }

}