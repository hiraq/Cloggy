<?php

App::uses('AppModel','Model');
App::uses('CloggyAppModel', 'Cloggy.Model');

class FakeModel extends CloggyAppModel {
    
    public $name = 'FakeModel';
    public $actsAs = array('Cloggy.CloggyCommon');
    public $useTable = false;
    
    public function getNode() {
        return $this->get('node');
    }
    
}

class CloggyCommonBehaviorTest extends CakeTestCase { 
    
    public $fixtures = array(
        'plugin.cloggy.cloggy_node',
        'plugin.cloggy.cloggy_node_type',
        'plugin.cloggy.cloggy_node_subject',
        'plugin.cloggy.cloggy_node_content',
        'plugin.cloggy.cloggy_node_permalink',
        'plugin.cloggy.cloggy_node_media',
        'plugin.cloggy.cloggy_node_meta',
        'plugin.cloggy.cloggy_node_rel',
        'plugin.cloggy.cloggy_user'
    );
    
    private $__FakeModel;

    public function setUp() {

        parent::setUp();
        $this->__FakeModel = ClassRegistry::init('FakeModel');
        
    }
    
    public function testObject() {
        $this->assertTrue(is_a($this->__FakeModel,'FakeModel'));
    }
    
    public function testCloggyNodeObject() {
        $Node = $this->__FakeModel->getNode();
        $this->assertTrue(is_a($Node,'CloggyNode'));
    }
    
    public function testNodeFind() {
        
        $Node = $this->__FakeModel->getNode();
        $data = $Node->find('all',array(
            'contain' => false
        ));
        
        $this->assertFalse(empty($data));
        $this->assertCount(4,$data);
        
    }
    
}