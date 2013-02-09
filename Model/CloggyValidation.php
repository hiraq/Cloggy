<?php

class CloggyValidation extends CloggyAppModel {

    public $name = 'CloggyValidation';
    public $useTable = false;

    /**
     * Check if given variables equal or not
     * @param string $check
     * @param string|int|boolean|array|object|null $result
     * @param string|int|boolean|array|object|null $expected
     * @return boolean
     */
    public function isValueEqual($check, $result, $expected) {
        return $result === $expected ? true : false;
    }

}