<?php

class ClogNodeSubjectFixture extends CakeTestFixture {
	
	public $useDbConfig = 'test';
	public $import = 'Clog.ClogNodeSubject';
	
	public function init() {
	
		$this->records = array(
			array(
				'id' => '1',
				'node_id' => '1',
				'subject' => 'test subject'
			),
			array(
				'id' => '2',
				'node_id' => '2',
				'subject' => 'test subject 2'
			),
			array(
				'id' => '3',
				'node_id' => '4',
				'subject' => 'test subject 3'
			),
		);
	
		parent::init();
	
	}
	
}