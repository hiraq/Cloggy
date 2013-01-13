<?php

App::uses('CloggyAppModel', 'Cloggy.Model');
App::uses('CloggyNode', 'Cloggy.Model');
App::uses('CloggyNodeContent', 'Cloggy.Model');

class CloggyNodeContentTest extends CakeTestCase {

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
    private $__CloggyNode;
    private $__CloggyNodeContent;

    public function setUp() {
        parent::setUp();
        $this->__CloggyNode = ClassRegistry::init('CloggyNode');
        $this->__CloggyNodeContent = ClassRegistry::init('CloggyNodeContent');
        $this->__CloggyNodeContent->cacheQueries = false;
        $this->__CloggyNode->cacheQueries = false;
    }

    public function testNodeObjects() {

        $this->assertFalse(empty($this->__CloggyNode));
        $this->assertFalse(empty($this->__CloggyNodeContent));
        $this->assertTrue(is_a($this->__CloggyNode, 'CloggyNode'));
        $this->assertTrue(is_a($this->__CloggyNodeContent, 'CloggyNodeContent'));
    }

    public function testNodeContent() {

        $data = $this->__CloggyNode->find('first', array(
            'contain' => array('CloggyContent'),
            'conditions' => array('CloggyNode.id' => 1)
                ));

        $this->assertFalse(empty($data));
        $this->assertArrayHasKey('CloggyNode', $data);
        $this->assertArrayHasKey('CloggyContent', $data);
        $this->assertFalse(empty($data['CloggyContent']));
        $this->assertFalse(empty($data['CloggyContent']['content']));
        $this->assertEqual($data['CloggyContent']['content'], 'test content');
    }

    public function testNodeContentCreate() {

        $nodeId = $this->__CloggyNode->generateEmptyNode(1, 1);
        $this->__CloggyNode->modifyNode($nodeId, array(
            'has_content' => 1
        ));

        $this->assertEqual($nodeId, 5);

        $this->__CloggyNodeContent->createContent($nodeId, 'test content 2');

        $data = $this->__CloggyNode->find('first', array(
            'contain' => array('CloggyContent'),
            'conditions' => array('CloggyNode.id' => $nodeId)
                ));

        $this->assertFalse(empty($data));
        $this->assertArrayHasKey('CloggyNode', $data);
        $this->assertArrayHasKey('CloggyContent', $data);
        $this->assertFalse(empty($data['CloggyContent']));
        $this->assertFalse(empty($data['CloggyContent']['content']));
        $this->assertEqual($data['CloggyContent']['content'], 'test content 2');
    }

    public function testRemoveContent() {

        $nodeId = $this->__CloggyNode->generateEmptyNode(1, 1);
        $this->__CloggyNode->modifyNode($nodeId, array(
            'has_content' => 1
        ));

        $this->assertEqual($nodeId, 5);

        $this->__CloggyNode->delete($nodeId, false);
        $this->__CloggyNodeContent->deleteAll(array(
            'CloggyNodeContent.node_id' => $nodeId
                ), false);

        $data = $this->__CloggyNodeContent->find('first', array(
            'contain' => false,
            'conditions' => array('CloggyNodeContent.node_id' => $nodeId)
                ));

        $dataNode = $this->__CloggyNode->find('first', array(
            'contain' => false,
            'conditions' => array('CloggyNode.id' => $nodeId)
                ));

        $this->assertTrue(empty($data));
        $this->assertTrue(empty($dataNode));
    }

}