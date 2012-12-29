<?php

class CloggyCommonBehavior extends ModelBehavior {		
	
	public function get(Model $model,$name) {
		
		$inflectedName = Inflector::camelize($name);				
		$className = 'Cloggy'.$inflectedName;		
		$class = ClassRegistry::init('Cloggy.'.$className);
		
		return $class;
		
	}
	
}