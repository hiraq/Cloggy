<?php

class ClogNodePermalink extends ClogAppModel {
	
	public $name = 'ClogNodePermalink';
	public $useTable = 'node_permalinks';
	
	public $belongsTo = array(
		'ClogNode' => array(
			'className' => 'Clog.ClogNode',
			'foreignKey' => 'node_id'
		)
	);
	
	public function createPermalink($nodeId,$subject,$separator='_') {
		$this->create();
		$this->save(array(
			'ClogNodePermalink' => array(
				'node_id' => $nodeId,
				'permalink_url' => Inflector::slug($subject,$separator)
			)
		));
	}
	
}