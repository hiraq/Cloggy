<?php

App::uses('Component', 'Controller');
App::uses('CloggyModuleConfigReader', 'CustomConfigure');

class CloggySearchSchemaComponent extends Component {
    
    private $__schema = array();    
    
    public function startup(Controller $controller) {
        
        parent::startup($controller);
        
        //configure config
        Configure::config('cloggy', new CloggyModuleConfigReader('CloggySearch'));                
        
    }        
    
    public function engine($engine) {
        
        App::uses('CloggySearchSchema'.$engine,'CloggySearchSchema');
        
        $className = 'CloggySearchSchema'.$engine;
        
        if (class_exists($className)) {
          
            $classObject = new $className;
        
            /*
             * only if object is an instance of CloggySearchSchemaBase
             */
            if (is_a($classObject,'CloggySearchSchemaBase')) {
                $this->__schema = $classObject->getSchema();
            }
            
        }        
        
    }
    
    public function getSchema() {
        return $this->__schema;
    }        
    
}