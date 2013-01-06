<?php

class CloggyNodeFixture extends CakeTestFixture {
	
	public $useDbConfig = 'test';
	public $import = array('model' => 'Cloggy.CloggyNode');
	
	public function init() {
		
		$this->records = array(
			array(
				'id' => 1,
				'node_type_id' => 1,
				'user_id' => 1,
				'node_parent' => 0,
				'has_subject' => 1,
				'has_content' => 1,
				'has_media' => 0,
				'has_meta' => 0,
				'node_status' => 1,
				'node_created' => date('c')
			),
			array(
				'id' => 2,
				'node_type_id' => 2,
				'user_id' => 1,
				'node_parent' => 0,
				'has_subject' => 1,
				'has_content' => 0,
				'has_media' => 0,
				'has_meta' => 0,
				'node_status' => 1,
				'node_created' => date('c')
			),
			array(
				'id' => 3,
				'node_type_id' => 1,
				'user_id' => 1,
				'node_parent' => 0,
				'has_subject' => 0,
				'has_content' => 0,
				'has_media' => 1,
				'has_meta' => 0,
				'node_status' => 1,
				'node_created' => date('c')
			),
			array(
				'id' => 4,
				'node_type_id' => 1,
				'user_id' => 1,
				'node_parent' => 0,
				'has_subject' => 0,
				'has_content' => 0,
				'has_media' => 0,
				'has_meta' => 1,
				'node_status' => 1,
				'node_created' => date('c')
			)
		);
		
		parent::init();
		
	}
	
}