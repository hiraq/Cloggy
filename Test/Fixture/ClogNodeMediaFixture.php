<?php

class ClogNodeMediaFixture extends CakeTestFixture {
	
	public $useDbConfig = 'test';
	public $import = 'Clog.ClogNodeMedia';
	
	public function init() {
		
		$this->records = array(
			array(
				'id' => '1',
				'node_id' => '3',
				'media_file_type' => 'image/jpg',
				'media_file_location' => 'http://placehold.it/300x300'
			)			
		);
		
		parent::init();
		
	}
	
}