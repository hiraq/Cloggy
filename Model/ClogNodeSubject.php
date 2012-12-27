<?php

class ClogNodeSubject extends ClogAppModel {
	
	public $name = 'ClogNodeSubject';
	public $useTable = 'node_subjects';
	
	public $belongsTo = array(
		'ClogNode' => array(
			'className' => 'Clog.ClogNode',
			'foreignKey' => 'node_id'
		)
	);
	
	public function createSubject($nodeId,$subject) {
		$this->create();
		$this->save(array(
			'ClogNodeSubject' => array(
				'node_id' => $nodeId,
				'subject' => $subject
			)
		));
	}
	
}