<?php

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