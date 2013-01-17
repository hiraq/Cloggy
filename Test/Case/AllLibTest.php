<?php

class AllLibTest extends CakeTestSuite {

    public static function suite() {
        $suite = new CakeTestSuite('All Cloggy lib tests');
        $suite->addTestDirectory(APP . 'Plugin' . DS . 'Cloggy' . DS .
                'Test' . DS . 'Case' . DS . 'Lib');
        return $suite;
    }

}