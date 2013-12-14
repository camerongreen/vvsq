<?php
/**
 * @file
 * vvsq_restaurant.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function vvsq_restaurant_ctools_plugin_api() {
  list($module, $api) = func_get_args();
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function vvsq_restaurant_views_api() {
  return array("version" => "3.0");
}

/**
 * Implements hook_node_info().
 */
function vvsq_restaurant_node_info() {
  $items = array(
    'restaurant' => array(
      'name' => t('Restaurant'),
      'base' => 'node_content',
      'description' => '',
      'has_title' => '1',
      'title_label' => t('Name'),
      'help' => '',
    ),
  );
  return $items;
}
