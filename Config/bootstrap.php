<?php

//setup clog admin prefix url
Configure::write('Cloggy.url_prefix', 'cloggy');

//setup clog admin themes
Configure::write('Cloggy.theme_used', 'default');

//setup registered modules
Configure::write('Cloggy.modules', array(
    'CloggyBlog', 'CloggyUsers', 'ModuleTest'
));

//setup clog module path
define('CLOGGY_PATH_MODULE', APP . 'Plugin' . DS . 'Cloggy' . DS . 'Module' . DS);

/**
 * Get cloggy base url
 * @return string
 */
function cloggyBaseUrl() {
    $base = '/' . Configure::read('Cloggy.url_prefix') . '/';
    return $base;
}

/**
 * Get cloggy url path
 * @param string $path
 * @return string
 */
function cloggyUrlPath($path) {
    $url = cloggyBaseUrl() . $path;
    return $url;
}

/**
 * Get module url
 * @param string $moduleName
 * @return string
 */
function cloggyUrlModule($moduleName, $path = null) {
    $base = '/' . Configure::read('Cloggy.url_prefix') . '/module/' . $moduleName;
    return is_null($path) ? $base : $base . '/' . $path;
}