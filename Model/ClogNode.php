<?php

class ClogNode extends ClogAppModel {
	
	public $useTable = 'nodes';
	public $name = 'ClogNode';
	
	public $belongsTo = array(
		'ClogType' => array(
			'className' => 'Clog.ClogNodeType',
			'foreignKey' => 'node_type_id'
		),
		'ClogUser' => array(
			'className' => 'Clog.ClogUser',
			'foreignKey' => 'user_id'
		),
		'ClogParentNode' => array(
			'className' => 'Clog.ClogNode',
			'foreignKey' => 'node_parent'
		)	
	);
	
	public $hasOne = array(	
		'ClogSubject' => array(
			'className' => 'Clog.ClogNodeSubject',
			'foreignKey' => 'node_id',
			'dependent' => false
		),
		'ClogContent' => array(
			'className' => 'Clog.ClogNodeContent',
			'foreignKey' => 'node_id',
			'dependent' => false
		),
		'ClogMedia' => array(
			'className' => 'Clog.ClogNodeMedia',
			'foreignKey' => 'node_id',
			'dependent' => false
		),
		'ClogPermalink' => array(
			'className' => 'Clog.ClogNodePermalink',
			'foreignKey' => 'node_id',
			'dependent' => false
		)
	);
	
	public $hasMany = array(					
		'ClogMeta' => array(
			'className' => 'Clog.ClogNodeMeta',
			'foreignKey' => 'node_id',
			'dependent' => false
		),		
		'ClogRelNode' => array(
			'className' => 'Clog.ClogNodeRel',
			'foreignKey' => 'node_id',
			'dependent' => false
		),
		'ClogRelObject' => array(
			'className' => 'Clog.ClogNodeRel',
			'foreignKey' => 'node_object_id',
			'dependent' => false
		)
	);	
	
	public function isSubjectExistsByTypeId($typeId,$subject) {
		
		$check = $this->ClogSubject->find('count',array(
			'contain' => array(
				'ClogNode' => array(
					'conditions' => array('ClogNode.node_type_id' => $typeId)
				)
			),
			'conditions' => array('ClogSubject.subject' => $subject)
		));
		
		return $check < 1 ? false : true;
		
	}
	
	public function getNodeIdBySubjectAndTypeId($typeId,$subject) {
		
		$data = $this->ClogSubject->find('first',array(
			'contain' => array(
				'ClogNode' => array(
					'conditions' => array('ClogNode.node_type_id' => $typeId)
				)
			),
			'conditions' => array('ClogSubject.subject' => $subject)
		));
		
		return !empty($data) ? $data['ClogNode']['id'] : false;	
		
	}		

	public function generateEmptyNode($typeId,$userId) {				
		
		$this->create();
		$this->save(array(
			'ClogNode' => array(
				'node_type_id' => $typeId,
				'user_id' => $userId,
				'node_created' => date('c')
			)
		));
		
		$nodeId = $this->id;
		return $nodeId;
		
	}
	
	public function modifyNode($nodeId,$data) {
		
		$this->id = $nodeId;
		$this->save(array(
			'ClogNode' => $data
		));
		
	}		
	
}