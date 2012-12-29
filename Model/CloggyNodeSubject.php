<?php

class CloggyNodeSubject extends CloggyAppModel {
	
	public $name = 'CloggyNodeSubject';
	public $useTable = 'node_subjects';
	
	public $belongsTo = array(
		'CloggyNode' => array(
			'className' => 'Cloggy.CloggyNode',
			'foreignKey' => 'node_id'
		)
	);
	
	public function createSubject($nodeId,$subject) {
		$this->create();
		$this->save(array(
			'CloggyNodeSubject' => array(
				'node_id' => $nodeId,
				'subject' => $subject
			)
		));
	}
	
}