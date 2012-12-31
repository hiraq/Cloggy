<?php

class AllComponentTest extends CakeTestSuite {
	
	public static function suite() {
		$suite = new CakeTestSuite('All Cloggy component tests');
		$suite->addTestDirectory(APP . 'Plugin' . DS . 'Cloggy'. DS. 
				'Test'. DS. 'Case'. DS.'Controller'.DS.'Component');
		return $suite;
	}
	
}