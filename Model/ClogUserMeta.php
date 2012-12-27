<?php

class ClogUserMeta extends ClogAppModel {
	
	public $name = 'ClogUserMeta';
	public $useTable = 'user_meta';
	
	public $belongsTo = array(
		'ClogUser' => array(
			'className' => 'Clog.ClogUser',
			'foreignKey' => 'user_id'
		)
	);
	
	public function isMetaExists($userId,$key) {
		
		$check = $this->find('count',array(
			'contain' => false,
			'conditions' => array(
				'ClogUserMeta.user_id' => $userId,
				'ClogUserMeta.meta_key' => $key
			)
		));
		
		return $check < 1 ? false : true;
		
	}
	
	public function getUserMeta($userId) {
		
		$data = $this->find('all',array(
			'contain' => false,
			'conditions' => array('ClogUserMeta.user_id' => $userId) 
		));
		
		return $data;
		
	}
	
	public function updateMeta($userId,$key,$value) {
		
		$check = $this->isMetaExists($userId, $key);
		if($check) {
			
			$data = $this->find('first',array(
				'contain' => false,
				'conditions' => array(
					'ClogUserMeta.user_id' => $userId,
					'ClogUserMeta.meta_key' => $key
				),
				'fields' => array('ClogUserMeta.id')
			));
			
			$this->id = $data['ClogUserMeta']['id'];
			$update = $this->saveField('meta_value',$value);

			return !empty($update) ? true : false;
			
		}
		
		return false;
		
	}
	
	public function saveMeta($userId,$data) {
		
		$returnIds = array();
		
		if(is_array($data) && !empty($data)) {
			
			foreach($data as $key => $value) {
				
				$check = $this->isMetaExists($userId, $key);
				if(!$check) {
					
					$this->create();
					$this->save(array(
						'ClogUserMeta' => array(
							'user_id' => $userId,
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
	
}