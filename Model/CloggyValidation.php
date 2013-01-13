<?php

class CloggyValidation extends CloggyAppModel {

    public $name = 'CloggyValidation';
    public $useTable = false;

    public function isValueEqual($check, $result, $expected) {
        return $result === $expected ? true : false;
    }

}