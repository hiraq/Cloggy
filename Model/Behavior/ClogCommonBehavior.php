<?php

class ClogCommonBehavior extends ModelBehavior {		
	
	public function get(Model $model,$name) {
		
		$inflectedName = Inflector::camelize($name);				
		$className = 'Clog'.$inflectedName;		
		$class = ClassRegistry::init('Clog.'.$className);
		
		return $class;
		
	}
	
}