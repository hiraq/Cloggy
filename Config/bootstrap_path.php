<?php

/*
 * custom path view
 */
$cloggyThemedViewPath = APP . 'Plugin' . DS . 'Cloggy' . DS . 'View' . DS . 'Themes' . DS . Configure::read('Cloggy.theme_used') . DS;

/*
 * custom path lib
 */
$cloggyLibPath = APP . 'Plugin' . DS . 'Cloggy' . DS . 'Lib' . DS;
$cloggyLibRouterPath = APP . 'Plugin' . DS . 'Cloggy' . DS . 'Lib' . DS . 'Router' . DS;

/*
 * custom acl component
 */
$cloggyAclPath = APP . 'Plugin' . DS . 'Cloggy' . DS . 'Controller' . DS . 'Components' . DS . 'Acl' . DS;

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
    'CustomRouter' => array($cloggyLibRouterPath)
        ), APP::REGISTER);

/*
 * delete variables
 */
unset($cloggyThemedViewPath);
unset($cloggyLibPath);
unset($cloggyLibRouterPath);