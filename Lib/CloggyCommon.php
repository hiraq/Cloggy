<?php

/**
 * Common functionalities used by Cloggy
 * 
 * @author hiraq
 * @package Cloggy
 * @subpackage Lib
 * @name CloggyCommon
 */
class CloggyCommon {
    
    /**
     * Setup base url
     * @access public
     * @return string
     */
    public static function baseUrl() {
        $base = '/' . Configure::read('Cloggy.url_prefix') . '/';
        return $base;
    }
    
    /**
     * Setup url to path inside Cloggy
     * 
     * @access public
     * @param string $path
     * @return string
     */
    public static function urlPath($path) {
        $url = self::baseUrl() . $path;
        return $url;
    }
    
    /**
     * Setup module url
     * 
     * @access public
     * @param string $moduleName
     * @param string $path [optional]
     * @return type
     */
    public static function urlModule($moduleName,$path=null) {
        $base = self::baseUrl() . 'module/' . Inflector::underscore($moduleName);
        return is_null($path) ? $base : $base . '/' . $path;
    }
    
}