<?php

class AllModelTest extends CakeTestSuite {

    public static function suite() {
        $suite = new CakeTestSuite('All Cloggy model tests');
        $suite->addTestDirectory(APP . 'Plugin' . DS . 'Cloggy' . DS .
                'Test' . DS . 'Case' . DS . 'Model');
        return $suite;
    }

}