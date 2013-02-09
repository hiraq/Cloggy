<?php

class CloggyNode extends CloggyAppModel {

    public $useTable = 'nodes';
    public $name = 'CloggyNode';
    public $belongsTo = array(
        'CloggyType' => array(
            'className' => 'Cloggy.CloggyNodeType',
            'foreignKey' => 'node_type_id'
        ),
        'CloggyUser' => array(
            'className' => 'Cloggy.CloggyUser',
            'foreignKey' => 'user_id'
        ),
        'CloggyParentNode' => array(
            'className' => 'Cloggy.CloggyNode',
            'foreignKey' => 'node_parent'
        )
    );
    public $hasOne = array(
        'CloggySubject' => array(
            'className' => 'Cloggy.CloggyNodeSubject',
            'foreignKey' => 'node_id',
            'dependent' => false
        ),
        'CloggyContent' => array(
            'className' => 'Cloggy.CloggyNodeContent',
            'foreignKey' => 'node_id',
            'dependent' => false
        ),
        'CloggyMedia' => array(
            'className' => 'Cloggy.CloggyNodeMedia',
            'foreignKey' => 'node_id',
            'dependent' => false
        ),
        'CloggyPermalink' => array(
            'className' => 'Cloggy.CloggyNodePermalink',
            'foreignKey' => 'node_id',
            'dependent' => false
        )
    );
    public $hasMany = array(
        'CloggyMeta' => array(
            'className' => 'Cloggy.CloggyNodeMeta',
            'foreignKey' => 'node_id',
            'dependent' => false
        ),
        'CloggyRelNode' => array(
            'className' => 'Cloggy.CloggyNodeRel',
            'foreignKey' => 'node_id',
            'dependent' => false
        ),
        'CloggyRelObject' => array(
            'className' => 'Cloggy.CloggyNodeRel',
            'foreignKey' => 'node_object_id',
            'dependent' => false
        )
    );

    /**
     * Check if given subject is exists or not
     * based on their node type
     * 
     * @param int $typeId
     * @param string $subject
     * @return boolean
     */
    public function isSubjectExistsByTypeId($typeId, $subject) {

        $check = $this->CloggySubject->find('first', array(
            'contain' => array(
                'CloggyNode' => array(
                    'conditions' => array('CloggyNode.node_type_id' => $typeId)
                )
            ),
            'conditions' => array('CloggySubject.subject' => $subject)
                ));

        return empty($check['CloggyNode']['id']) ? false : true;
    }

    /**
     * Get node id by their subject and node type
     * @param int $typeId
     * @param string $subject
     * @return int|boolean
     */
    public function getNodeIdBySubjectAndTypeId($typeId, $subject) {

        $data = $this->CloggySubject->find('first', array(
            'contain' => array(
                'CloggyNode' => array(
                    'conditions' => array('CloggyNode.node_type_id' => $typeId)
                )
            ),
            'conditions' => array('CloggySubject.subject' => $subject)
                ));

        return !empty($data) ? $data['CloggyNode']['id'] : false;
    }

    /**
     * Generate empty node
     * 
     * @param int $typeId
     * @param int $userId
     * @return int
     */
    public function generateEmptyNode($typeId, $userId) {

        $this->create();
        $this->save(array(
            'CloggyNode' => array(
                'node_type_id' => $typeId,
                'user_id' => $userId,
                'node_created' => date('c')
            )
        ));

        $nodeId = $this->id;
        return $nodeId;
    }

    /**
     * Update existed node
     * @param int $nodeId
     * @param array $data
     */
    public function modifyNode($nodeId, $data) {

        $this->id = $nodeId;
        $this->save(array(
            'CloggyNode' => $data
        ));
    }

}