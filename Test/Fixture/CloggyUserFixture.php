<?php

class CloggyUserFixture extends CakeTestFixture {
	
	public $useDbConfig = 'test';
	public $import = array('model' => 'Cloggy.CloggyUser');
	
	public function init() {
		
		$this->records = array(
			array(
				'id' => 1,
				'user_name' => 'test user',
				'user_password' => 'test',
				'user_email' => 'test@test.com',
				'user_role' => 'test role',
				'user_status' => 1,
				'user_last_login' => date('c'),
				'user_created' => date('c')
			)
		);
		parent::init();
		
	}
	
}