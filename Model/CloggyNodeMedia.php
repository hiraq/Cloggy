<?php

class CloggyNodeMedia extends CloggyAppModel {

    public $name = 'CloggyNodeMedia';
    public $useTable = 'node_media';
    public $belongsTo = array(
        'CloggyNode' => array(
            'className' => 'Cloggy.CloggyNode',
            'foreignKey' => 'node_id'
        )
    );

    /**
     * Create and save new media
     * 
     * @param int $nodeId
     * @param string $type
     * @param string $location
     */
    public function saveMedia($nodeId, $type, $location) {

        $this->create();
        $this->save(array(
            'CloggyNodeMedia' => array(
                'node_id' => $nodeId,
                'media_file_type' => $type,
                'media_file_location' => $location
            )
        ));
        
    }

}