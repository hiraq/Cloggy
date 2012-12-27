<?php

class ClogUserFixture extends CakeTestFixture {
	
	public $useDbConfig = 'test';
	public $import = 'Clog.ClogUser';
	
	public function init() {				
		
		$this->records = array(
			array(
				'id' => '1',
				'user_name' => 'test user',
				'user_password' => 'testing',
				'user_email' => 'test@email.com',
				'user_role' => 'super administrator',
				'user_status' => '1',
				'user_last_login' => date('c'),
				'user_created' => date('c'),
				'user_updated' => time()
			)
		);
		
		parent::init();
		
	}
	
}