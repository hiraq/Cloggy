<?php

class ClogNodeType extends ClogAppModel {
	
	public $name = 'ClogNodeType';
	public $useTable = 'node_types';
	
	public $belongsTo = array(
		'ClogUser' => array(
			'className' => 'Clog.ClogUser',
			'foreignKey' => 'user_id'
		)
	);
	
	public $hasMany = array(
		'ClogNode' => array(
			'className' => 'Clog.ClogNode',
			'foreignKey' => 'node_type_id',
			'dependent' => false
		)
	);
	
	public function isTypeExists($name) {
		
		$check = $this->find('count',array(
			'contain' => false,
			'conditions' => array('ClogNodeType.node_type_name' => $name)
		));
		
		return $check < 1 ? false : true;
		
	}
	
	public function generateType($name,$userId) {
		
		$check = $this->isTypeExists($name);
		if(!$check) {

			$this->create();
			$this->save(array(
				'ClogNodeType' => array(
					'user_id' => $userId,
					'node_type_name' => $name,
					'node_type_desc'  => 'no desc',
					'node_type_created' => date('c')
				)
			));
			
			return $this->id;
			
		}else{
			$id = $this->getTypeIdByName($name);
			return $id;
		}							
		
	}
	
	public function getTypeIdByName($name) {
		
		$data = $this->find('first',array(
			'contain' => false,
			'conditions' => array('ClogNodeType.node_type_name' => $name),
			'fields' => array('ClogNodeType.id')
		));
		
		if(!empty($data)) {
			return $data['ClogNodeType']['id'];
		}else{
			return false;
		}
		
	}
	
}