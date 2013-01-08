<?php

class CloggyNodePermalink extends CloggyAppModel {
	
	public $name = 'CloggyNodePermalink';
	public $useTable = 'node_permalinks';
	
	public $belongsTo = array(
		'CloggyNode' => array(
			'className' => 'Cloggy.CloggyNode',
			'foreignKey' => 'node_id'
		)
	);
	
	public function createPermalink($nodeId,$subject,$separator='_') {
		
		$this->create();
		$this->save(array(
			'CloggyNodePermalink' => array(
				'node_id' => $nodeId,
				'permalink_url' => Inflector::slug(trim($subject),$separator)
			)
		));
		
		return $this->id;
	}
	
}