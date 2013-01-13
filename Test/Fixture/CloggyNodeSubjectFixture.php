<?php

class CloggyNodeSubjectFixture extends CakeTestFixture {

    public $useDbConfig = 'test';
    public $import = array('model' => 'Cloggy.CloggyNodeSubject');

    public function init() {

        $this->records = array(
            array(
                'id' => 1,
                'node_id' => 1,
                'subject' => 'test subject has content'
            ),
            array(
                'id' => 2,
                'node_id' => 2,
                'subject' => 'test subject term'
            )
        );

        parent::init();
    }

}