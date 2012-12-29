<?php

class DashboardController extends CloggyAppController {
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->deny('*');
	}
	
	public function index() {						
		$this->set('title_for_layout','Cloggy - Administration Dashboard');				
	}
	
}