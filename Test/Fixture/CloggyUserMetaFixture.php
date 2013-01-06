<?php

class CloggyUserMetaFixture extends CakeTestFixture {
	
	public $useDbConfig = 'test';
	public $import = array('model' => 'Cloggy.CloggyUserMeta');
	
	public function init() {
		
		$this->records = array(
			array(
				'id' => 1,
				'user_id' => 1,
				'meta_key' => 'test user key',
				'meta_value' => 'test user value'
			)
		);
		
		parent::init();
		
	}
	
}