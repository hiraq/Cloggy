<?php

class ClogNodeMetaFixture extends CakeTestFixture {
	
	public $useDbConfig = 'test';
	public $import = 'Clog.ClogNodeMeta';
	
	public function init() {
		
		$this->records = array(
			array(
				'id' => '1',
				'node_id' => '4',
				'meta_key' => 'test meta',
				'meta_value' => 'test_value'
			),
			array(
				'id' => '2',
				'node_id' => '4',
				'meta_key' => 'test meta 2',
				'meta_value' => 'test_value 2'
			)
		);
		
		parent::init();
		
	}
	
}