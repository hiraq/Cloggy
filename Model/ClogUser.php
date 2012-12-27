<?php

class ClogUser extends ClogAppModel {
	
	public $name = 'ClogUser';
	public $useTable = 'users';	
	
	public $hasOne = array(
		'ClogUserLogin' => array(
			'className' => 'Clog.ClogUserLogin',
			'foreignKey' => 'user_id',
			'dependent' => false
		)
	);
	
	public $hasMany = array(
		'ClogNode' => array(
			'className' => 'Clog.ClogNode',
			'foreignKey' => 'user_id',
			'dependent' => false
		),
		'ClogUserMeta' => array(
			'className' => 'Clog.ClogUserMeta',
			'foreignKey' => 'user_id',
			'dependent' => false
		),
		'ClogNodeType' => array(
			'className' => 'Clog.ClogNodeType',
			'foreignKey' => 'user_id',
			'dependent' => false
		)
	);
	
	public function isUserNameExists($name) {
		
		$check = $this->find('count',array(
			'contain' => false,
			'conditions' => array('ClogUser.user_name' => $name)
		));
		
		return $check < 1 ? false : true;
		
	}
	
	public function isUserEmailExists($email) {
		
		$check = $this->find('count',array(
			'contain' => false,
			'conditions' => array('ClogUser.user_email' => $email)
		));
		
		return $check < 1 ? false : true;
		
	}
	
	public function setUserLastLogin($id) {
		
		$this->id = $id;
		$this->save(array(
			'ClogUser' => array(
				'user_last_login' => date('c')
			)
		));
		
	}
	
	public function getUserDetail($id) {
		return $this->find('first',array(
			'contain' => false,
			'conditions' => array(
				'ClogUser.id' => $id
			)
		));
	}
	
	public function getUserStatus($id) {
		
		$data = $this->find('first',array(
			'contain' => false,
			'conditions' => array('ClogUser.id' => $id),
			'fields' => array('ClogUser.user_status')
		));
		
		return $data;
		
	}
	
	public function getUserLastLogin($id) {
		
		$data = $this->find('first',array(
			'contain' => false,
			'conditions' => array('ClogUser.id' => $id),
			'fields' => array('ClogUser.user_last_login')
		));
		
		return $data;
		
	}
	
	public function getUserRole($id) {
		
		$data = $this->find('first',array(
			'contain' => false,
			'conditions' => array('ClogUser.id' => $id),
			'fields' => array('ClogUser.user_role')
		));
		
		return $data;
		
	}
	
}