<?php

//setup component main path
$componentMainPath = APP . 'Plugin' . DS . 'Cloggy' . DS . 'Module' . DS.'CloggySearch'.DS.'Controller'.DS.'Component'.DS;

/*
 * built path
 */
App::build(array(
    'CloggySearchSchema' => array($componentMainPath.'CloggySearchSchema'.DS),
    'CloggySearchEngine' => array($componentMainPath.'CloggySearchEngine'.DS)
),APP::REGISTER);