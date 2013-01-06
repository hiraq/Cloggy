<?php

class CloggyNodePermalinkFixture extends CakeTestFixture {
	
	public $useDbConfig = 'test';
	public $import = array('model' => 'Cloggy.CloggyNodePermalink');
	
	public function init() {
		
		$this->records = array(
			array(
				'id' => 1,
				'node_id' => 1,
				'permalink_url' => 'test-subject-has-content'
			)
		);
		
		parent::init();
		
	}
	
}