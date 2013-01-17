<?php

/*
 * custom path view
 */
$cloggyThemedViewPath = APP . 'Plugin' . DS . 'Cloggy' . DS . 'View' . DS . 
        'Themes' . DS . Configure::read('Cloggy.theme_used') . DS;

/*
 * custom path lib
 */
$cloggyLibPath = APP . 'Plugin' . DS . 'Cloggy' . DS . 'Lib' . DS;
$cloggyLibRouterPath = $cloggyLibPath . 'Router' . DS;
$cloggyLibReaderPath = $cloggyLibPath . 'Configure' . DS;

/*
 * setup cake custom path
 */
App::build(array(
    'View' => array($cloggyThemedViewPath),
    'Lib' => array($cloggyLibPath)
), APP::APPEND);

/*
 * register new custom packages
 */
App::build(array(
    'CustomRouter' => array($cloggyLibRouterPath),
    'CustomConfigure' => array($cloggyLibReaderPath)
), APP::REGISTER);

/*
 * delete variables
 */
unset($cloggyThemedViewPath);
unset($cloggyLibPath);
unset($cloggyLibRouterPath);
unset($cloggyLibReaderPath);

//load CloggyCommon class
App::uses('CloggyCommon','Lib');