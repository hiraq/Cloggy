<?php

class AllHelperTest extends CakeTestSuite {

    public static function suite() {
        $suite = new CakeTestSuite('All Cloggy helper tests');
        $suite->addTestDirectory(APP . 'Plugin' . DS . 'Cloggy' . DS .
                'Test' . DS . 'Case' . DS . 'View' . DS . 'Helper');
        return $suite;
    }

}