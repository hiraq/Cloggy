<?php

class CloggyAppModel extends AppModel {

    public $actsAs = array('Containable');
    public $cacheQueries = true;      
    public $tablePrefix = 'cloggy_';

}