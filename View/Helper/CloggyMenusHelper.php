<?php

App::uses('AppHelper', 'View/Helper');

/**
 * 
 * Configure and load cloggy menus
 * for views
 * 
 * @author hiraq
 * @name CloggyMenusHelper
 * @package Cloggy
 * @subpackage Helper
 */
class CloggyMenusHelper extends AppHelper {

  /**
   * 
   * Save View variables
   * 
   * @access private
   * @var array
   */
  private $__vars;

  /**
   * Used helpers
   * 
   * @access public
   * @var array
   */
  public $helpers = array('Html');

  /**
   * Get View variables
   * 
   * @access public
   * @return array
   */
  public function getViewVars() {
    $this->__vars = $this->_View->getVars();
    return $this->__vars;
  }

  /**
   * Get View variable value
   * 
   * @access public
   * @param string $key
   * @return string|array
   */
  public function getViewVarValue($key) {
    return $this->_View->getVar($key);
  }

  /**
   * 
   * Create html link
   * 
   * @access public
   * @param string $key
   * @param string $url
   * @return string
   */
  public function getLink($key, $url) {
    return $this->Html->link(ucfirst(strtolower($key)), Router::url($url, true));
  }

  /**
   * Get http url by Router configuration
   * 
   * @access public
   * @param string $url
   * @return string
   */
  public function getUrl($url) {
    return Router::url($url, true);
  }

  /**
   * 
   * Get menus for views
   * 
   * @access public
   * @param string $key
   * @param string $group [optional]
   * @return null|array|boolean
   */
  public function menu($key, $group = 'cloggy_menus') {
    $menus = $this->getViewVarValue($group);
    if ($menus) {

      $return = false;
      if (array_key_exists($key, $menus)) {
        if (!empty($menus[$key])) {
          $return = $menus[$key];
        }
      }

      return $return;
    }

    return false;
  }

  /**
   * 
   * Get group menus by key
   * @access public
   * @param string $key
   * @return array|boolean
   */
  public function group($key) {

    $menus = $this->menu($key, 'cloggy_menus_group');
    if ($menus) {
      return $menus;
    }

    return false;
  }

  /**
   * Get all grouped menus
   * @access public
   * @return array|boolean
   */
  public function groups() {

    $vars = $this->getViewVarValue('cloggy_menus_group');
    if (!empty($vars)) {
      return $vars;
    }

    return false;
  }

}