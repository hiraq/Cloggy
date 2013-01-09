<?php

App::uses('AclInterface', 'Controller/Component/Acl');

class CloggyModuleAcl extends Object implements AclInterface {
	
	public function check($aro, $aco, $action = "*") {
		
	}
	
	public function allow($aro, $aco, $action = "*") {
		
	}
	
	public function deny($aro, $aco, $action = "*") {
		
	}
	
	public function inherit($aro, $aco, $action = "*") {
		
	}
	
	public function initialize(Component $component) {
		
	}
	
}