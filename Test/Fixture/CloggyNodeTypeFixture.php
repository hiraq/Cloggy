<?php

class CloggyNodeTypeFixture extends CakeTestFixture {
	
	public $useDbConfig = 'test';
	public $import = array('model' => 'Cloggy.CloggyNodeType');
	
	public function init() {
		
		$this->records = array(
			array(
				'id' => 1,
				'user_id' => 1,
				'node_type_name' => 'test_content_type',
				'node_type_desc' => 'node has subject and content',
				'node_type_created' => date('c')
			),
			array(
				'id' => 2,
				'user_id' => 1,
				'node_type_name' => 'test_term_type',
				'node_type_desc' => 'node has subject',
				'node_type_created' => date('c')
			)
		);
		
		parent::init();
		
	}
	
}