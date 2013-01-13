<?php

class CloggyNodeRelFixture extends CakeTestFixture {

    public $useDbConfig = 'test';
    public $import = array('model' => 'Cloggy.CloggyNodeRel');

    public function init() {

        $this->records = array(
            array(
                'id' => 1,
                'node_id' => 2,
                'node_object_id' => 1,
                'relation_name' => 'test rels',
                'relation_created' => date('c')
            )
        );

        parent::init();
    }

}