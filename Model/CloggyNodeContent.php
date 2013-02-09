<?php

class CloggyNodeContent extends CloggyAppModel {

    public $name = 'CloggyNodeContent';
    public $useTable = 'node_contents';
    public $belongsTo = array(
        'CloggyNode' => array(
            'className' => 'Cloggy.CloggyNode',
            'foreignKey' => 'node_id'
        )
    );

    /**
     * Create new content data
     * 
     * @param int $nodeId
     * @param string $content
     */
    public function createContent($nodeId, $content) {
        $this->create();
        $this->save(array(
            'CloggyNodeContent' => array(
                'node_id' => $nodeId,
                'content' => $content
            )
        ));
    }

}