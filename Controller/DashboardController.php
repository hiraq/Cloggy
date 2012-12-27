<?php

class DashboardController extends ClogAppController {
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->deny('*');
	}
	
	public function index() {						
		$this->set('title_for_layout','Clog - Administration Dashboard');				
	}
	
}