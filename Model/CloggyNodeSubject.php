<?php

class CloggyNodeSubject extends CloggyAppModel {

    public $name = 'CloggyNodeSubject';
    public $useTable = 'node_subjects';
    public $belongsTo = array(
        'CloggyNode' => array(
            'className' => 'Cloggy.CloggyNode',
            'foreignKey' => 'node_id'
        )
    );

    /**
     * Create and save new subject
     * @param int $nodeId
     * @param string $subject
     * @return int
     */
    public function createSubject($nodeId, $subject) {

        $this->create();
        $this->save(array(
            'CloggyNodeSubject' => array(
                'node_id' => $nodeId,
                'subject' => $subject
            )
        ));

        return $this->id;
    }

}