<?php

class ClogNodeRelFixture extends CakeTestFixture {
	
	public $useDbConfig = 'test';
	public $import = 'Clog.ClogNodeRel';
	
	public function init() {
		
		$this->records = array(
			array(
				'id' => '1',
				'node_id' => '1',
				'node_object_id' => '4',
				'relation_name' => 'test relation',
				'relation_created' => date('c')
			)	
		);
		
		parent::init();
		
	}
	
}