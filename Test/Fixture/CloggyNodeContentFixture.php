<?php

class CloggyNodeContentFixture extends CakeTestFixture {
	
	public $useDbConfig = 'test';
	public $import = array('model' => 'Cloggy.CloggyNodeContent');
	
	public function init() {
	
		$this->records = array(
			array(
				'id' => 1,
				'node_id' => 1,
				'content' => 'test content'				
			)			
		);
	
		parent::init();
	
	}
	
}