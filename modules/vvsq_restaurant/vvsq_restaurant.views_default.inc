<?php
/**
 * @file
 * vvsq_restaurant.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function vvsq_restaurant_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'restaurants';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'node';
  $view->human_name = 'Restaurants';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Restaurants';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['group_by'] = TRUE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '1000';
  $handler->display->display_options['style_plugin'] = 'list';
  $handler->display->display_options['row_plugin'] = 'fields';
  $handler->display->display_options['row_options']['default_field_elements'] = FALSE;
  /* Field: Content: State */
  $handler->display->display_options['fields']['field_state']['id'] = 'field_state';
  $handler->display->display_options['fields']['field_state']['table'] = 'field_data_field_state';
  $handler->display->display_options['fields']['field_state']['field'] = 'field_state';
  $handler->display->display_options['fields']['field_state']['label'] = '';
  $handler->display->display_options['fields']['field_state']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_state']['element_label_colon'] = FALSE;
  /* Field: Content: Area */
  $handler->display->display_options['fields']['field_area']['id'] = 'field_area';
  $handler->display->display_options['fields']['field_area']['table'] = 'field_data_field_area';
  $handler->display->display_options['fields']['field_area']['field'] = 'field_area';
  $handler->display->display_options['fields']['field_area']['label'] = '';
  $handler->display->display_options['fields']['field_area']['alter']['make_link'] = TRUE;
  $handler->display->display_options['fields']['field_area']['alter']['path'] = 'restaurants-in/[field_area]';
  $handler->display->display_options['fields']['field_area']['element_label_colon'] = FALSE;
  /* Field: COUNT(Content: Area) */
  $handler->display->display_options['fields']['field_area_1']['id'] = 'field_area_1';
  $handler->display->display_options['fields']['field_area_1']['table'] = 'field_data_field_area';
  $handler->display->display_options['fields']['field_area_1']['field'] = 'field_area';
  $handler->display->display_options['fields']['field_area_1']['group_type'] = 'count';
  $handler->display->display_options['fields']['field_area_1']['label'] = '';
  $handler->display->display_options['fields']['field_area_1']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_area_1']['separator'] = ', ';
  $handler->display->display_options['fields']['field_area_1']['prefix'] = '(';
  $handler->display->display_options['fields']['field_area_1']['suffix'] = ')';
  /* Sort criterion: Content: State (field_state) */
  $handler->display->display_options['sorts']['field_state_value']['id'] = 'field_state_value';
  $handler->display->display_options['sorts']['field_state_value']['table'] = 'field_data_field_state';
  $handler->display->display_options['sorts']['field_state_value']['field'] = 'field_state_value';
  /* Sort criterion: Content: Area (field_area) */
  $handler->display->display_options['sorts']['field_area_value']['id'] = 'field_area_value';
  $handler->display->display_options['sorts']['field_area_value']['table'] = 'field_data_field_area';
  $handler->display->display_options['sorts']['field_area_value']['field'] = 'field_area_value';
  /* Filter criterion: Content: Published */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = 1;
  $handler->display->display_options['filters']['status']['group'] = 1;
  $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'restaurant' => 'restaurant',
  );
  /* Filter criterion: Content: State (field_state) */
  $handler->display->display_options['filters']['field_state_value']['id'] = 'field_state_value';
  $handler->display->display_options['filters']['field_state_value']['table'] = 'field_data_field_state';
  $handler->display->display_options['filters']['field_state_value']['field'] = 'field_state_value';
  $handler->display->display_options['filters']['field_state_value']['value'] = 'QLD';

  /* Display: Page */
  $handler = $view->new_display('page', 'Page', 'page');
  $handler->display->display_options['path'] = 'restaurants';
  $export['restaurants'] = $view;

  $view = new view();
  $view->name = 'restaurants_in';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'node';
  $view->human_name = 'Restaurants in';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Restaurants in';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['exposed_form']['options']['reset_button'] = TRUE;
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '1000';
  $handler->display->display_options['style_plugin'] = 'default';
  $handler->display->display_options['row_plugin'] = 'fields';
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  /* Field: Content: Vegetarian/Vegan */
  $handler->display->display_options['fields']['field_vegetarian_vegan']['id'] = 'field_vegetarian_vegan';
  $handler->display->display_options['fields']['field_vegetarian_vegan']['table'] = 'field_data_field_vegetarian_vegan';
  $handler->display->display_options['fields']['field_vegetarian_vegan']['field'] = 'field_vegetarian_vegan';
  $handler->display->display_options['fields']['field_vegetarian_vegan']['label'] = '';
  $handler->display->display_options['fields']['field_vegetarian_vegan']['element_label_colon'] = FALSE;
  /* Field: Field: Rating */
  $handler->display->display_options['fields']['field_rating']['id'] = 'field_rating';
  $handler->display->display_options['fields']['field_rating']['table'] = 'field_data_field_rating';
  $handler->display->display_options['fields']['field_rating']['field'] = 'field_rating';
  $handler->display->display_options['fields']['field_rating']['label'] = '';
  $handler->display->display_options['fields']['field_rating']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_rating']['click_sort_column'] = 'rating';
  $handler->display->display_options['fields']['field_rating']['settings'] = array(
    'widget' => array(
      'fivestar_widget' => 'sites/all/modules/fivestar/widgets/hearts/hearts.css',
    ),
    'expose' => 0,
    'style' => 'average',
    'text' => 'average',
  );
  /* Sort criterion: Content: Vegetarian/Vegan (field_vegetarian_vegan) */
  $handler->display->display_options['sorts']['field_vegetarian_vegan_value']['id'] = 'field_vegetarian_vegan_value';
  $handler->display->display_options['sorts']['field_vegetarian_vegan_value']['table'] = 'field_data_field_vegetarian_vegan';
  $handler->display->display_options['sorts']['field_vegetarian_vegan_value']['field'] = 'field_vegetarian_vegan_value';
  $handler->display->display_options['sorts']['field_vegetarian_vegan_value']['order'] = 'DESC';
  $handler->display->display_options['sorts']['field_vegetarian_vegan_value']['expose']['label'] = 'Restaurant Type';
  /* Sort criterion: Field: Rating (field_rating:rating) */
  $handler->display->display_options['sorts']['field_rating_rating']['id'] = 'field_rating_rating';
  $handler->display->display_options['sorts']['field_rating_rating']['table'] = 'field_data_field_rating';
  $handler->display->display_options['sorts']['field_rating_rating']['field'] = 'field_rating_rating';
  $handler->display->display_options['sorts']['field_rating_rating']['order'] = 'DESC';
  $handler->display->display_options['sorts']['field_rating_rating']['expose']['label'] = 'Rating';
  /* Sort criterion: Content: Title */
  $handler->display->display_options['sorts']['title']['id'] = 'title';
  $handler->display->display_options['sorts']['title']['table'] = 'node';
  $handler->display->display_options['sorts']['title']['field'] = 'title';
  /* Contextual filter: Content: Area (field_area) */
  $handler->display->display_options['arguments']['field_area_value']['id'] = 'field_area_value';
  $handler->display->display_options['arguments']['field_area_value']['table'] = 'field_data_field_area';
  $handler->display->display_options['arguments']['field_area_value']['field'] = 'field_area_value';
  $handler->display->display_options['arguments']['field_area_value']['default_action'] = 'not found';
  $handler->display->display_options['arguments']['field_area_value']['title_enable'] = TRUE;
  $handler->display->display_options['arguments']['field_area_value']['title'] = 'Restaurants in %1';
  $handler->display->display_options['arguments']['field_area_value']['breadcrumb_enable'] = TRUE;
  $handler->display->display_options['arguments']['field_area_value']['breadcrumb'] = '%1';
  $handler->display->display_options['arguments']['field_area_value']['default_argument_type'] = 'fixed';
  $handler->display->display_options['arguments']['field_area_value']['summary']['number_of_records'] = '0';
  $handler->display->display_options['arguments']['field_area_value']['summary']['format'] = 'default_summary';
  $handler->display->display_options['arguments']['field_area_value']['summary_options']['items_per_page'] = '25';
  $handler->display->display_options['arguments']['field_area_value']['limit'] = '0';
  /* Filter criterion: Content: Published */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = 1;
  $handler->display->display_options['filters']['status']['group'] = 1;
  $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'restaurant' => 'restaurant',
  );
  /* Filter criterion: Content: Vegetarian/Vegan (field_vegetarian_vegan) */
  $handler->display->display_options['filters']['field_vegetarian_vegan_value']['id'] = 'field_vegetarian_vegan_value';
  $handler->display->display_options['filters']['field_vegetarian_vegan_value']['table'] = 'field_data_field_vegetarian_vegan';
  $handler->display->display_options['filters']['field_vegetarian_vegan_value']['field'] = 'field_vegetarian_vegan_value';
  $handler->display->display_options['filters']['field_vegetarian_vegan_value']['value'] = array(
    'vegan' => 'vegan',
    'vegetarian' => 'vegetarian',
    'mostly_vegetarian' => 'mostly_vegetarian',
    'mixed' => 'mixed',
  );
  $handler->display->display_options['filters']['field_vegetarian_vegan_value']['exposed'] = TRUE;
  $handler->display->display_options['filters']['field_vegetarian_vegan_value']['expose']['operator_id'] = 'field_vegetarian_vegan_value_op';
  $handler->display->display_options['filters']['field_vegetarian_vegan_value']['expose']['label'] = 'Restaurant Type';
  $handler->display->display_options['filters']['field_vegetarian_vegan_value']['expose']['operator'] = 'field_vegetarian_vegan_value_op';
  $handler->display->display_options['filters']['field_vegetarian_vegan_value']['expose']['identifier'] = 'field_vegetarian_vegan_value';
  $handler->display->display_options['filters']['field_vegetarian_vegan_value']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['field_vegetarian_vegan_value']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
  );

  /* Display: Page */
  $handler = $view->new_display('page', 'Page', 'page');
  $handler->display->display_options['exposed_block'] = TRUE;
  $handler->display->display_options['path'] = 'restaurants-in';
  $export['restaurants_in'] = $view;

  return $export;
}
