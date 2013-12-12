<?php
/**
 * @file
 * vvsq_menus.features.menu_custom.inc
 */

/**
 * Implements hook_menu_default_menu_custom().
 */
function vvsq_menus_menu_default_menu_custom() {
  $menus = array();

  // Exported menu: menu-top-menu.
  $menus['menu-top-menu'] = array(
    'menu_name' => 'menu-top-menu',
    'title' => 'Top menu',
    'description' => 'Menu at top of each page above logo',
  );
  // Translatables
  // Included for use with string extractors like potx.
  t('Menu at top of each page above logo');
  t('Top menu');


  return $menus;
}
