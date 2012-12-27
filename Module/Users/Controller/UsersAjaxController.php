<?php

App::uses('ClogAppController','Clog.Controller');

class UsersAjaxController extends ClogAppController {

	public $uses = array('Clog.ClogUser');
	
	public function beforeFilter() {
	
		parent::beforeFilter();
		$this->Auth->deny('*');
		
		if(!$this->request->is('ajax')) {
			$this->redirect('/');
		}
		
		$this->autoRender = false;
			
	}
	
	public function delete_all() {				

		$users = $this->request->data['user'];
		foreach($users as $user) {
			$this->ClogUser->delete($user,false);
		}
		
		echo json_encode(array('msg' => 'success'));
		
	}
	
	public function disable_all() {
		
		$users = $this->request->data['user'];
		foreach($users as $user) {
			
			$this->ClogUser->id = $user;
			$this->ClogUser->save(array(
				'ClogUser' => array(
					'user_status' => 0
				)
			));
			
		}
		
		echo json_encode(array('msg' => 'success'));
		
	}
	
	public function enable_all() {
		
		$users = $this->request->data['user'];
		foreach($users as $user) {
				
			$this->ClogUser->id = $user;
			$this->ClogUser->save(array(
					'ClogUser' => array(
							'user_status' => 1
					)
			));
				
		}
		
		echo json_encode(array('msg' => 'success'));
		
	}
	
}