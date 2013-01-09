<?php

App::uses('Component', 'Controller');

class CloggyAclComponent extends Component {
	
	private $__CloggyModuleAcl;
	private $__Controller;
	
	public function __construct(ComponentCollection $collection, $settings = array()) {
		
		parent::__construct($collection,$settings);
		App::uses('CloggyModuleAcl','Cloggy.Controller/Component/Acl');
		
		$this->__CloggyModuleAcl = new CloggyModuleAcl();
		
	}
	
	public function initialize(Controller $controller) {
		$this->__Controller = $controller;		
	}
	
}