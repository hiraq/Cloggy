<?php

class CloggyNodeMediaFixture extends CakeTestFixture {

    public $useDbConfig = 'test';
    public $import = array('model' => 'Cloggy.CloggyNodeMedia');

    public function init() {

        $this->records = array(
            array(
                'id' => 1,
                'node_id' => 3,
                'media_file_type' => 'image/jpeg',
                'media_file_location' => '/upload/test_file.jpg'
            )
        );

        parent::init();
    }

}