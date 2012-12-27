<?php

class ClogNodeTypeFixture extends CakeTestFixture {
	
	public $useDbConfig = 'test';
	public $import = 'Clog.ClogNodeType';
	
	public function init() {
		
		$this->records = array(
			array(
				'id' => '1',
				'user_id' => '1',
				'node_type_name' => 'node type name',
				'node_type_desc' => 'node type desc',
				'node_type_created' => date('c')
			),
			array(
				'id' => '2',
				'user_id' => '1',
				'node_type_name' => 'node type name 2',
				'node_type_desc' => 'node type desc 2',
				'node_type_created' => date('c')
			)
		);
		
		parent::init();
		
	}
	
}