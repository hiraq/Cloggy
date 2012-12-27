<?php

/*
 * custom path view
*/
$clogThemedViewPath = APP.'Plugin'.DS.'Clog'.DS.'View'.DS.'Themes'.DS.Configure::read('Clog.theme_used').DS;

/*
 * custom path lib
*/
$clogLibPath = APP.'Plugin'.DS.'Clog'.DS.'Lib'.DS;
$clogLibRouterPath = APP.'Plugin'.DS.'Clog'.DS.'Lib'.DS.'Router'.DS;

/*
 * setup cake custom path
*/
App::build(array(
	'View'			=> array($clogThemedViewPath),
	'Lib'			=> array($clogLibPath)	
),APP::APPEND);

/*
 * register new custom packages
*/
App::build(array(
		'CustomRouter'	=> array($clogLibRouterPath)
),APP::REGISTER);

/*
 * delete variables
*/
unset($clogThemedViewPath);
unset($clogLibPath);
unset($clogLibRouterPath);