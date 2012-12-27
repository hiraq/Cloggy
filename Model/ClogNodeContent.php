<?php

class ClogNodeContent extends ClogAppModel {
	
	public $name = 'ClogNodeContent';
	public $useTable = 'node_contents';
	
	public $belongsTo = array(
		'ClogNode' => array(
			'className' => 'Clog.ClogNode',
			'foreignKey' => 'node_id'
		)
	);
	
	public function createContent($nodeId,$content) {
		$this->create();
		$this->save(array(
			'ClogNodeContent' => array(
				'node_id' => $nodeId,
				'content' => $content
			)
		));
	}
	
}