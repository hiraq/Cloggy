<?php

class CloggyNodeMetaFixture extends CakeTestFixture {
	
	public $useDbConfig = 'test';
	public $import = array('model' => 'Cloggy.CloggyNodeMeta');
	
	public function init() {
		
		$this->records = array(
			array(
				'id' => 1,
				'node_id' => 4,
				'meta_key' => 'test key',
				'meta_value' => 'test value'
			),
			array(
				'id' => 2,
				'node_id' => 4,
				'meta_key' => 'test key 2',
				'meta_value' => 'test value 2'
			)
		);
		
		parent::init();
		
	}
	
}