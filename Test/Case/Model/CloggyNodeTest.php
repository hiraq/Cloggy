<?php

App::uses('CloggyAppModel', 'Cloggy.Model');
App::uses('CloggyNode', 'Cloggy.Model');

class CloggyNodeTest extends CakeTestCase {

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

    public function setUp() {

        parent::setUp();
        $this->__CloggyNode = ClassRegistry::init('CloggyNode');
    }

    public function testModelFindUnContainable() {

        $data = $this->__CloggyNode->find('first', array(
            'contain' => false
                ));

        $this->assertFalse(empty($data));
        $this->assertArrayHasKey('CloggyNode', $data);
        $this->assertArrayHasKey('id', $data['CloggyNode']);
        $this->assertEqual(1, $data['CloggyNode']['id']);
    }

    public function testModelFindContainable() {

        $data = $this->__CloggyNode->find('first');

        $this->assertFalse(empty($data));
        $this->assertArrayHasKey('CloggyNode', $data);
        $this->assertArrayHasKey('CloggyType', $data);
    }

    public function testNodeSubjectContent() {

        $data = $this->__CloggyNode->find('first', array(
            'contain' => array(
                'CloggySubject',
                'CloggyContent'
            ),
            'conditions' => array(
                'CloggyNode.id' => 1
            )
                ));

        $this->assertFalse(empty($data));
        $this->assertArrayHasKey('CloggyNode', $data);
        $this->assertArrayHasKey('CloggySubject', $data);
        $this->assertArrayHasKey('CloggyContent', $data);
        $this->assertEqual('test subject has content', $data['CloggySubject']['subject']);
        $this->assertEqual('test content', $data['CloggyContent']['content']);
    }

    public function testNodeSubjectOnly() {

        $data = $this->__CloggyNode->find('first', array(
            'contain' => array(
                'CloggySubject'
            ),
            'conditions' => array(
                'CloggyNode.id' => 2
            )
                ));

        $this->assertFalse(empty($data));
        $this->assertArrayHasKey('CloggyNode', $data);
        $this->assertArrayHasKey('CloggySubject', $data);
        $this->assertEqual('test subject term', $data['CloggySubject']['subject']);
    }

    public function testSubjectExists() {

        $checkSubject = $this->__CloggyNode->isSubjectExistsByTypeId(2, 'test subject term');
        $checkSubjectFake = $this->__CloggyNode->isSubjectExistsByTypeId(2, 'test subject term fake');
        $checkSubjectDiffType = $this->__CloggyNode->isSubjectExistsByTypeId(1, 'test subject term');

        $this->assertTrue($checkSubject);
        $this->assertFalse($checkSubjectFake);
        $this->assertFalse($checkSubjectDiffType);
    }

    public function testGetNodeIdBySubjectAndTypeId() {
        $nodeId = $this->__CloggyNode->getNodeIdBySubjectAndTypeId(2, 'test subject term');
        $this->assertEqual(2, $nodeId);
    }

    public function testGenerateEmptyNode() {
        $nodeId = $this->__CloggyNode->generateEmptyNode(2, 1);
        $this->assertEqual(5, $nodeId);
    }

    public function testModifyNode() {

        $nodeId = $this->__CloggyNode->generateEmptyNode(2, 1);

        $this->__CloggyNode->modifyNode($nodeId, array(
            'has_subject' => 1,
            'has_content' => 1,
            'node_type_id' => 1
        ));

        $data = $this->__CloggyNode->find('first', array(
            'contain' => false,
            'conditions' => array('CloggyNode.id' => $nodeId)
                ));

        $this->assertFalse(empty($data));
        $this->assertEqual(1, $data['CloggyNode']['has_subject']);
        $this->assertEqual(1, $data['CloggyNode']['node_type_id']);
    }

    public function testNodeMedia() {

        $data = $this->__CloggyNode->find('first', array(
            'contain' => array('CloggyMedia'),
            'conditions' => array('CloggyNode.id' => 3)
                ));

        $this->assertFalse(empty($data));
        $this->assertArrayHasKey('CloggyMedia', $data);
        $this->assertEqual($data['CloggyMedia']['media_file_type'], 'image/jpeg');
        $this->assertEqual($data['CloggyMedia']['media_file_location'], '/upload/test_file.jpg');
    }

    public function testNodeMeta() {

        $data = $this->__CloggyNode->find('first', array(
            'contain' => array('CloggyMeta'),
            'conditions' => array('CloggyNode.id' => 4)
                ));

        $this->assertFalse(empty($data));
        $this->assertArrayHasKey('CloggyMeta', $data);
        $this->assertCount(2, $data['CloggyMeta']);
    }

    public function testNodeObject() {

        $data = $this->__CloggyNode->find('first', array(
            'contain' => array('CloggyRelObject'),
            'conditions' => array('CloggyNode.id' => 1)
                ));

        $this->assertFalse(empty($data));
        $this->assertArrayHasKey('CloggyRelObject', $data);
        $this->assertEqual($data['CloggyRelObject'][0]['relation_name'], 'test rels');

        $data = $this->__CloggyNode->find('first', array(
            'contain' => array('CloggyRelNode'),
            'conditions' => array('CloggyNode.id' => 2)
                ));

        $this->assertFalse(empty($data));
        $this->assertArrayHasKey('CloggyRelNode', $data);
        $this->assertEqual($data['CloggyRelNode'][0]['relation_name'], 'test rels');
    }

    public function testRemoveNode() {

        $this->__CloggyNode->cacheQueries = false;
        $nodeId = $this->__CloggyNode->generateEmptyNode(2, 1);
        $this->__CloggyNode->delete($nodeId, false);

        $data = $this->__CloggyNode->find('count', array('contain' => false));
        $this->assertEqual(4, $data);
    }

}