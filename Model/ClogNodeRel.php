<?php

class ClogNodeRel extends ClogAppModel {
	
	public $name = 'ClogNodeRel';
	public $useTable = 'node_rels';
	
	public $belongsTo = array(
		'ClogNode' => array(
			'className' => 'Clog.ClogNode',
			'foreignKey' => 'node_id'
		),
		'ClogNodeObject' => array(
			'className' => 'Clog.ClogNode',
			'foreignKey' => 'node_object_id'
		)
	);
	
	public function isRelationExists($nodeId,$nodeObjectId,$relName) {
		
		$check = $this->find('count',array(
			'conditions' => array(
				'ClogNodeRel.node_id' => $nodeId,
				'ClogNodeRel.node_object_id' => $nodeObjectId,
				'ClogNodeRel.relation_name' => $relName
			)
		));
		
		return $check < 1 ? false : true;
		
	}
	
	public function deleteAllRelations($nodeObjectId,$rel) {
		
		$this->deleteAll(array(
			'ClogNodeRel.node_object_id' => $nodeObjectId,
				'ClogNodeRel.relation_name' => $rel
		));
		
	}
	
	public function saveRelation($nodeId,$nodeObjectId,$relName) {
		
		$check = $this->isRelationExists($nodeId, $nodeObjectId, $relName);
		if(!$check) {
			
			$this->create();
			$this->save(array(
				'ClogNodeRel' => array(
					'node_id' => $nodeId,
					'node_object_id' => $nodeObjectId,
					'relation_name' => $relName,
					'relation_created' => date('c')
				)
			));
			
			return $this->id;
			
		}
		
		return false;
		
	}
	
}