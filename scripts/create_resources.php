<?php

// get $old_database - standard drupal connection array
include_once("../scripts/db_details.php");

/**
 * A script to import the resources, just going to create a node type and add a taxonomy
 * label to them
 *
 * You will need to run like this :
 *  APPLICATION_ENV="development" drush php-script create_resources.php
 *
 * User: cameron
 * Date: 18/11/13
 * Time: sunset :)
 */

define('RESOURCES_VOCABULARY_NAME', "Resources");
define('RESOURCES_VOCABULARY_MACHINE_NAME', "resources");
define('RESOURCE_OWNER', 'Dark Horse');
define('RESOURCE_NODE_TYPE', 'resource');
define('RESOURCE_POST_FORMAT', 'filtered_html');

Database::addConnectionInfo('import', 'default', $old_database);
db_set_active('import');

$resources = db_query('SELECT *, UNIX_TIMESTAMP(Resource_Add_Date) AS created FROM {Resources}');

db_set_active();

$newVocab = taxonomy_vocabulary_machine_name_load(RESOURCES_VOCABULARY_MACHINE_NAME);

if (!$newVocab) {
  $newVocab = new stdClass();
  $newVocab->name = RESOURCES_VOCABULARY_NAME;
  $newVocab->machine_name = RESOURCES_VOCABULARY_MACHINE_NAME;
  taxonomy_vocabulary_save($newVocab);
}

$account = user_load_by_name(RESOURCE_OWNER);
$account_uid = $account ? $account->uid : 0;

foreach ($resources as $resource) {
  $terms = taxonomy_get_term_by_name($resource->resource_type, RESOURCES_VOCABULARY_MACHINE_NAME);

  if ($terms) {
    $term = $terms[0];
  }
  else {
    $term = new stdClass();
    $term->name = $resource->resource_type;
    $term->vid = $newVocab->vid;
    $term->parent = 0;
    taxonomy_term_save($term);
  }

  $newResource = new stdClass();
  $newResource->nid = NULL;
  $newResource->title = $resource->Resouce_Title;
  $newResource->type = RESOURCE_NODE_TYPE;
  node_object_prepare($newResource);

  $newResource->language = LANGUAGE_NONE;
  $newResource->body[$newResource->language][0]['summary'] = text_summary($resource->Resource_Description);
  $newResource->body[$newResource->language][0]['format'] = RESOURCE_POST_FORMAT;
  $newResource->created = $resource->created; // see query
  $newResource->revision_timestamp = $resource->created;
  $newResource->uid = $account_uid;
  $newResource->status = 1;
  $newResource->promote = 0;
  $newResource->sticky = 0;
  $newResource->format = RESOURCE_POST_FORMAT;
  $newResource->taxonomy[$newResource->language][0]['tid'] = $term->tid;
  $newResource->body[$newResource->language][0]['value'] = check_markup($resource->Resource_Description, RESOURCE_POST_FORMAT);
  node_save($newResource);

  // node_save forces created time to be NOW(), so update
  // it also forces the uid to be the current user (root of this script) so update that too
  $revision_uid_updated = db_update('node_revision')
    ->fields(array(
      'uid' => $account_uid,
      'timestamp' => $resource->created ? $resource->created : time(),
    ))
    ->condition('nid', $newResource->nid, '=')
    ->execute();

  // also fix the node dates
  db_update('node')
    ->fields(array(
      'created' => $resource->created ? $resource->created : time(),
      'changed' => 0
    ))
    ->condition('nid', $newResource->nid, '=')
    ->execute();
}
