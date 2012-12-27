<?php

class ClogNodeMeta extends ClogAppModel {
	
	public $name = 'ClogNodeMeta';
	public $useTable = 'node_meta';
	
	public $belongsTo = array(
		'ClogNode' => array(
			'className' => 'Clog.ClogNode',
			'foreignKey' => 'node_id'
		)
	);
	
	public function getNodeMeta($nodeId) {
		
		$data = $this->find('all',array(
			'contain' => false,
			'conditions' => array(
				'ClogNodeMeta.node_id' => $nodeId
			)
		));
		
		return $data;
		
	}
	
	public function updateMeta($nodeId,$key,$value) {
		
		$check = $this->isMetaExists($nodeId, $key);
		
		if($check) {
			
			$data = $this->find('first',array(
				'contain' => false,
				'conditions' => array(
					'ClogNodeMeta.node_id' => $nodeId,
					'ClogNodeMeta.meta_key' => $key
				),
				'fields' => array('ClogNodeMeta.id')
			));
			
			$this->id = $data['ClogNodeMeta']['id'];
			$update = $this->saveField('meta_value', $value);
				
			return !empty($update) ? true : false;
			
		}
		
		return false;
		
	}
	
	public function saveMeta($nodeId,$data) {
		
		$returnIds = array();
		
		if(is_array($data) && !empty($data)) {
			
			foreach($data as $key => $value) {
				
				$checkExists = $this->isMetaExists($nodeId, $key);
								
				if(!$checkExists) {
					
					$this->create();
					$this->save(array(
						'ClogNodeMeta' => array(
							'node_id' => $nodeId,
							'meta_key' => $key,
							'meta_value' => $value
						)
					));
					
					$returnIds[$key] = $this->id;
					
				}
				
			}
			
			return $returnIds;
			
		}
		
		return false;
		
	}
	
	public function isMetaExists($nodeId,$metaKey) {
		
		$check = $this->find('count',array(
			'contain' => false,
			'conditions' => array(
				'ClogNodeMeta.node_id' => $nodeId,
				'ClogNodeMeta.meta_key' => $metaKey
			)
		));
		
		return $check < 1 ? false : true;
		
	}
	
}