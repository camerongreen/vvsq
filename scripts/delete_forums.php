<?php

// get $old_database - standard drupal connection array
include_once("../scripts/db_details.php");

/**
 * A script to unpopulate the Drupal Forums
 *
 * You will need to run like this :
 *  APPLICATION_ENV="development" drush php-script delete_forums.php
 *
 * User: cameron
 * Date: 13/12/13
 * Time: 12:19 PM
 */

define('FORUM_VID', 2);
define('FORUM_CONTENT_TYPE', 'forum');
define('FORUM_NID_PAGE', 100);

// first delete all the taxonomy terms
$terms = taxonomy_get_tree(FORUM_VID);

foreach ($terms as $term) {
  taxonomy_term_delete($term->tid);
}

// now delete all the nodes
$result = db_query('SELECT nid FROM {node} WHERE type = :type ', array(':type' => FORUM_CONTENT_TYPE));

$nid_subset = array();

foreach ($result as $nid) {
  if (count($nid_subset) === FORUM_NID_PAGE) {
    node_delete_multiple($nid_subset);
    $nid_subset = array();
  }
  $nid_subset[] = $nid->nid;
}

node_delete_multiple($nid_subset);

