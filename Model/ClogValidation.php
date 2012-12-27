<?php

class ClogValidation extends ClogAppModel {
	
	public $name = 'ClogValidation';
	public $useTable = false;	
	
	public function isValueEqual($check,$result,$expected) {
		return $result === $expected ? true : false;
	}
	
}