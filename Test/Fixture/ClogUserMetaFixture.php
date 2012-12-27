<?php

class ClogUserMetaFixture extends CakeTestFixture {
	
	public $useDbConfig = 'test';
	public $import = 'Clog.ClogUserMeta';
	
	public function init() {
		
		$this->records = array(
			array(
				'id' => '1',
				'user_id' => '1',
				'meta_key' => 'key 1',
				'meta_value' => 'value 1'
			)
		);
		parent::init();
		
	}
	
}