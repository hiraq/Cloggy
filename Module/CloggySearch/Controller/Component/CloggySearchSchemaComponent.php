<?php

App::uses('Component', 'Controller');
App::uses('CloggyModuleConfigReader', 'CustomConfigure');

class CloggySearchSchemaComponent extends Component {
    
    private $__schema = array();
    private $__model = null;
    
    public function startup(Controller $controller) {
        
        parent::startup($controller);
        
        //configure config
        Configure::config('cloggy', new CloggyModuleConfigReader('CloggySearch'));
        
        //load schema
        Configure::load('schema_mysqlfull_text','cloggy');
        
        //get schema
        $this->__schema = Configure::read('Cloggy.CloggySearch.schema_mysqlfull_text');        
        
    }        
    
    public function getSchema() {
        return $this->__schema;
    }
    
    public function getModel() {
        return $this->__model;
    }
    
    public function setModel(CloggyAppModel $modelIndex) {
        $this->__model = $modelIndex;
    }
    
}