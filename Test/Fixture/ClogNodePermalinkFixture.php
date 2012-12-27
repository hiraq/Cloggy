<?php

class ClogNodePermalinkFixture extends CakeTestFixture {
	
	public $useDbConfig = 'test';
	public $import = 'Clog.ClogNodePermalink';
	
	public function init() {
		
		$this->records = array(
			array(
				'id' => '1',
				'node_id' => '1',
				'permalink_url' => 'test-subject'
			),
			array(
				'id' => '2',
				'node_id' => '2',
				'permalink_url' => 'test-subject-2'
			),
			array(
				'id' => '3',
				'node_id' => '4',
				'permalink_url' => 'test-subject-3'
			),
		);
		
		parent::init();
		
	}
	
}