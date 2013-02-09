<?php

class CloggyNodeRel extends CloggyAppModel {

    public $name = 'CloggyNodeRel';
    public $useTable = 'node_rels';
    public $belongsTo = array(
        'CloggyNode' => array(
            'className' => 'Cloggy.CloggyNode',
            'foreignKey' => 'node_id'
        ),
        'CloggyNodeObject' => array(
            'className' => 'Cloggy.CloggyNode',
            'foreignKey' => 'node_object_id'
        )
    );

    /**
     * Check if relation exists or not
     * @param int $nodeId
     * @param int $nodeObjectId
     * @param string $relName
     * @return boolean
     */
    public function isRelationExists($nodeId, $nodeObjectId, $relName) {

        $check = $this->find('count', array(
            'conditions' => array(
                'CloggyNodeRel.node_id' => $nodeId,
                'CloggyNodeRel.node_object_id' => $nodeObjectId,
                'CloggyNodeRel.relation_name' => $relName
            )
                ));

        return $check < 1 ? false : true;
    }

    /**
     * Delete all available relation based on node object id
     * and their relation name
     * 
     * @param int $nodeObjectId
     * @param string $rel
     */
    public function deleteAllRelations($nodeObjectId, $rel) {

        $this->deleteAll(array(
            'CloggyNodeRel.node_object_id' => $nodeObjectId,
            'CloggyNodeRel.relation_name' => $rel
        ));
    }

    /**
     * Create and save new relation
     * @param int $nodeId
     * @param int $nodeObjectId
     * @param string $relName
     * @return boolean
     */
    public function saveRelation($nodeId, $nodeObjectId, $relName) {

        $check = $this->isRelationExists($nodeId, $nodeObjectId, $relName);
        if (!$check) {

            $this->create();
            $this->save(array(
                'CloggyNodeRel' => array(
                    'node_id' => $nodeId,
                    'node_object_id' => $nodeObjectId,
                    'relation_name' => $relName,
                    'relation_created' => date('c')
                )
            ));

            return $this->id;
        }

        return false;
    }

}