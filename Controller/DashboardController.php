<?php

class DashboardController extends CloggyAppController {
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->deny('*');
	}
	
	public function index() {	

		$this->CloggyModuleMenu->add('cloggy_sidebar',array(
			'Add New User' => $this->_base.'/module/cloggy_users/cloggy_users_home/add',
			'Edit My Profile' => $this->_base.'/module/cloggy_users/cloggy_users_home/edit/'.$this->Auth->user('id'),
		));
		
		$this->set('cloggy_sidebar_title','Shortcuts');
		$this->set('title_for_layout','Cloggy - Administration Dashboard');
						
	}
	
}