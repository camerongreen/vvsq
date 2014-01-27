<?php
/**
 * Created by PhpStorm.
 * User: cameron
 * Date: 06/11/13
 * Time: 20:27
 */

/**
 * Implements hook_preprocess_page().
 */
function vegsoc_preprocess_page(&$variables) {
  $path = '/' . path_to_theme();

  drupal_add_html_head(array(
    '#tag' => 'link',
    '#attributes' => array(
      'href' => $path . '/images/icons/favicon.ico',
      'rel' => 'shortcut icon',
      'sizes' => '16x16 32x32 48x48 64x64'
    ),
  ), 'vegsoc-favicon');

  drupal_add_html_head(array(
    '#tag' => 'link',
    '#attributes' => array(
      'href' => $path . '/images/icons/favicon.ico',
      'rel' => 'shortcut icon',
      'type' => 'image/x-icon'
    ),
  ), 'vegsoc-favicon-2');

  drupal_add_html_head(array(
    '#tag' => 'link',
    '#attributes' => array(
      'href' => $path . '/images/icons/favicon-57.png',
      'rel' => 'apple-touch-icon',
    ),
  ), 'vegsoc-touch-icon-iphone');

  drupal_add_html_head(array(
    '#tag' => 'link',
    '#attributes' => array(
      'href' => $path . '/images/icons/favicon-72.png',
      'sizes' => '72x72',
      'rel' => 'apple-touch-icon',
    ),
  ), 'vegsoc-touch-icon-ipad');

  drupal_add_html_head(array(
    '#tag' => 'link',
    '#attributes' => array(
      'href' => $path . '/images/icons/favicon-96.png',
      'sizes' => '96x96',
      'type' => 'image/png',
      'rel' => 'icon',
    ),
  ), 'vegsoc-google-tv');

  drupal_add_html_head(array(
    '#tag' => 'link',
    '#attributes' => array(
      'href' => $path . '/images/icons/favicon-114.png',
      'sizes' => '114x114',
      'rel' => 'apple-touch-icon',
    ),
  ), 'vegsoc-touch-icon-iphone-retina');

  drupal_add_html_head(array(
    '#tag' => 'link',
    '#attributes' => array(
      'href' => $path . '/images/icons/favicon-120.png',
      'sizes' => '120x120',
      'rel' => 'apple-iphone-icon',
    ),
  ), 'vegsoc-touch-icon-iphone-retina');

  drupal_add_html_head(array(
    '#tag' => 'meta',
    '#attributes' => array(
      'content' => $path . '/images/icons/favicon-144.png',
      'name' => 'msapplication-TileImage',
    ),
  ), 'vegsoc-windows-8-tiles');

  drupal_add_html_head(array(
    '#tag' => 'meta',
    '#attributes' => array(
      'content' => '#FFFFFF',
      'name' => 'msapplication-TileColor',
    ),
  ), 'vegsoc-windows-8-tiles-color');

  drupal_add_html_head(array(
    '#tag' => 'link',
    '#attributes' => array(
      'href' => $path . '/images/icons/favicon-144.png',
      'sizes' => '144x144',
      'rel' => 'apple-touch-icon',
    ),
  ), 'vegsoc-touch-icon-ipad-retina');

  drupal_add_html_head(array(
    '#tag' => 'link',
    '#attributes' => array(
      'href' => $path . '/images/icons/favicon-152.png',
      'sizes' => '152x152',
      'rel' => 'apple-touch-icon',
    ),
  ), 'vegsoc-touch-icon-ipad-ios7-retina');

  drupal_add_html_head(array(
    '#tag' => 'link',
    '#attributes' => array(
      'href' => $path . '/images/icons/favicon-195.png',
      'sizes' => '195x195',
      'rel' => 'icon',
      'type' => 'image/png',
    ),
  ), 'vegsoc-opera-speed-dial');
}


/**
 * Implementation of CKEditor default height (http://groups.drupal.org/node/170324)
 */
function vegsoc_wysiwyg_editor_settings_alter(&$settings, $context) {
  if($context['profile']->editor == 'ckeditor') {
    $settings['height'] = 200;
  }
}