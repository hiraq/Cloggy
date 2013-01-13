<?php

App::uses('CloggyAppModel', 'Cloggy.Model');
App::uses('CloggyNode', 'Cloggy.Model');
App::uses('CloggyNodeType', 'Cloggy.Model');

class CloggyNodeTypeTest extends CakeTestCase {

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
  private $__CloggyNodeType;

  public function setUp() {
    parent::setUp();
    $this->__CloggyNode = ClassRegistry::init('CloggyNode');
    $this->__CloggyNodeType = ClassRegistry::init('CloggyNodeType');
    $this->__CloggyNodeType->cacheQueries = false;
    $this->__CloggyNode->cacheQueries = false;
  }

  public function testNodeObjects() {

    $this->assertFalse(empty($this->__CloggyNode));
    $this->assertFalse(empty($this->__CloggyNodeType));
    $this->assertTrue(is_a($this->__CloggyNode, 'CloggyNode'));
    $this->assertTrue(is_a($this->__CloggyNodeType, 'CloggyNodeType'));
  }

  public function testTypeExists() {

    $check = $this->__CloggyNodeType->isTypeExists('test_content_type');
    $checkFake = $this->__CloggyNodeType->isTypeExists('test_content_type_2');

    $this->assertTrue($check);
    $this->assertFalse($checkFake);
  }

  public function testGetTypeId() {

    $typeId = $this->__CloggyNodeType->getTypeIdByName('test_content_type');
    $typeIdFake = $this->__CloggyNodeType->getTypeIdByName('test_content_type_2');

    $this->assertEqual($typeId, 1);
    $this->assertFalse($typeIdFake);
  }

  public function testGenerateNodeType() {

    $typeId = $this->__CloggyNodeType->generateType('test_type', 1);
    $typeIdExists = $this->__CloggyNodeType->generateType('test_content_type', 1);

    $this->assertEqual(3, $typeId);
    $this->assertEqual(1, $typeIdExists);
  }

  public function testRemoveNodeType() {

    $this->__CloggyNodeType->delete(1, false);
    $data = $this->__CloggyNodeType->find('first', array(
        'contain' => false,
        'conditions' => array('CloggyNodeType.id' => 1)
            ));

    $this->assertTrue(empty($data));
  }

}