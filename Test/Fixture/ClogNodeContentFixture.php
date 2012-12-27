<?php

class ClogNodeContentFixture extends CakeTestFixture {
	
	public $useDbConfig = 'test';
	public $import = 'Clog.ClogNodeContent';
	
	public function init() {
		
		$this->records = array(
			array(
				'id' => '1',
				'node_id' => '1',
				'content' => 'test content 1',							
			),
			array(
				'id' => '2',
				'node_id' => '3',
				'content' => 'test content 2',
			),
			array(
				'id' => '3',
				'node_id' => '4',
				'content' => 'test content 3',
			)
		);
		
		parent::init();
		
	}
	
}