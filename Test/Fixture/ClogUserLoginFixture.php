<?php

class ClogUserLoginFixture extends CakeTestFixture {
	
	public $useDbConfig = 'test';
	public $import = 'Clog.ClogUserLogin';
	
	public function init() {
		
		$this->records = array(
			array(
				'id' => '1',
				'user_id' => '1',
				'login_datetime' => date('c')
			)
		);
		
		parent::init();
		
	}
	
}