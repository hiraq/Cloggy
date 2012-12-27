<?php

class ClogNodeFixture extends CakeTestFixture {
	
	public $useDbConfig = 'test';
	public $import = 'Clog.ClogNode';
	
	public function init() {
		
		$this->records = array(
			array(
				'id' => '1',
				'node_type_id' => '1',
				'user_id' => '1',
				'node_parent' => '0',
				'node_created' => date('c'),
				'has_subject' => '1',
				'has_content' => '1',
				'has_media' => '0',
				'has_meta' => '0',
				'node_status' => '0',
				'node_created' => date('c'),
				'node_updated' => time()
			),
			array(
				'id' => '2',
				'node_type_id' => '2',
				'user_id' => '1',
				'node_parent' => '1',
				'node_created' => date('c'),
				'has_subject' => '1',
				'has_content' => '0',
				'has_media' => '0',
				'has_meta' => '0',
				'node_status' => '0',
				'node_created' => date('c'),
				'node_updated' => time()
			),
			array(
				'id' => '3',
				'node_type_id' => '1',
				'user_id' => '1',
				'node_parent' => '0',
				'node_created' => date('c'),
				'has_subject' => '0',
				'has_content' => '1',
				'has_media' => '0',
				'has_meta' => '0',
				'node_status' => '0',
				'node_created' => date('c'),
				'node_updated' => time()
			),
			array(
				'id' => '4',
				'node_type_id' => '1',
				'user_id' => '1',
				'node_parent' => '0',
				'node_created' => date('c'),
				'has_subject' => '1',
				'has_content' => '1',
				'has_media' => '0',
				'has_meta' => '1',
				'node_status' => '0',
				'node_created' => date('c'),
				'node_updated' => time()
			),
		);
		
		parent::init();
		
	}
	
}