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
        
        //load schema
        Configure::load($engine,'cloggy');
        
        //get schema
        $schema = Configure::read('Cloggy.CloggySearch.'.$engine);        
        
        if ($schema) {
            $this->__schema = $schema;
        }
        
    }
    
    public function getSchema() {
        return $this->__schema;
    }        
    
}