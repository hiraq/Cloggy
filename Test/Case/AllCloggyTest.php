<?php

//require_once dirname(__FILE__) . DS . 'CloggyGroupTestCase.php';
//
///**
// * @link https://github.com/cakephp/debug_kit/blob/master/Test/Case/AllDebugKitTest.php follow DebugKit group test case
// */
//class AllCloggyTest extends CloggyGroupTestCase {
//
//	public static function suite() {
//		$suite = new self;
//		$files = $suite->getTestFiles();
//		$suite->addTestFiles($files);
//
//		return $suite;
//	}
//}

class AllCloggyTest extends CakeTestSuite {

    public static function suite() {
        
        $suite = new CakeTestSuite('All Cloggy tests');
        
        /*
         * components
         */
        $suite->addTestDirectory(APP . 'Plugin' . DS . 'Cloggy' . DS .
                'Test' . DS . 'Case');                
                
        return $suite;
    }           

}