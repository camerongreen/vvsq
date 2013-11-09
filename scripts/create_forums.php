<?php

// get $old_database - standard drupal connection array
include_once("../scripts/db_details.php");

/**
 * A script to popuplate the Drupal Forums with entries from the old forums
 *
 * You will need to run like this :
 *  APPLICATION_ENV="development" drush php-script create_forums.php
 *
 * User: cameron
 * Date: 08/11/13
 * Time: 12:19 PM
 */

// Limit import settings
define('MAX_TOPICS', 100000); // only allow this many topics to be imported
define('MAX_THREADS', 100000); // only allow this many threads to be imported for each topic
define('MAX_MESSAGES', 100000); // only allow this many messages to be imported for each thread

define('FORUM_VID', 2);
define('POST_FORMAT', 'filtered_html');

Database::addConnectionInfo('import', 'default', $old_database);
db_set_active('import');

$result = db_query('SELECT * FROM {topic} tp WHERE tp.enabled = :active LIMIT ' . MAX_TOPICS, array(':active' => 1));
$topics = $result->fetchAllAssoc('ID');

db_set_active();

// Result is returned as a iterable object that returns a stdClass object on each iteration
foreach ($topics as $topic) {
  $newTopic = new stdClass();
  $newTopic->name = $topic->title;
  $newTopic->description = $topic->description;
  $newTopic->vid = FORUM_VID;
  $newTopic->parent = 0;
  taxonomy_term_save($newTopic);

  db_set_active('import');
  $threads = db_query('SELECT *, UNIX_TIMESTAMP(date_created) AS created FROM {Thread} WHERE Topic_ID = :topic_id AND date_created IS NOT NULL LIMIT ' . MAX_THREADS, array(':topic_id' => $topic->ID));
  db_set_active();

  foreach ($threads as $thread) {
    $account = user_load_by_name($thread->started_by);
    $account_uid = $account ? $account->uid : 0;

    $newThread = new stdClass();
    $newThread->nid = NULL;
    $newThread->title = $thread->title ? $thread->title : "Untitled";
    $newThread->type = 'forum';
    node_object_prepare($newThread);

    $newThread->language = LANGUAGE_NONE;
    $newThread->body[$newThread->language][0]['summary'] = text_summary($thread->description);
    $newThread->body[$newThread->language][0]['format'] = POST_FORMAT;
    $newThread->created = $thread->created; // see query
    $newThread->revision_timestamp = $thread->created;
    $newThread->uid = $account_uid;
    $newThread->status = 1;
    $newThread->promote = 0;
    $newThread->sticky = 0;
    $newThread->format = POST_FORMAT;

    //$newThread->taxonomy_forums[$newThread->language][0]['tid'] = FORUM_VID;
    $newThread->taxonomy_forums[$newThread->language][0]['tid'] = $newTopic->tid;
    $newThread->comments = $thread->locked == 1 ? 1 : 2;

    db_set_active('import');
    $messages = db_query('SELECT Messages.*, userdetails.Username, UNIX_TIMESTAMP(Date_Entered) AS created FROM {Messages} JOIN {userdetails} ON User_ID = userdetails.Author_ID WHERE Thread_ID = :thread_id ORDER BY created LIMIT ' . MAX_MESSAGES, array(':thread_id' => $thread->Id));
    db_set_active();

    // the first message in each Thread is the original post, so add that
    // to the node rather than as a comment
    $first = TRUE;
    // get last comment timestamp for comment stats table
    $last_updated = NULL;

    foreach ($messages as $message) {
      $last_updated = $message->created;
      if ($first) {
        $newThread->body[$newThread->language][0]['value'] = check_markup($message->Message, POST_FORMAT);
        node_save($newThread);
        // node_save forces created time to be NOW(), so update
        // it also forces the uid to be the current user (root of this script) so update that too
        $revision_uid_updated = db_update('node_revision')
          ->fields(array(
            'uid' => $account_uid,
            'timestamp' => $thread->created,
          ))
          ->condition('nid', $newThread->nid, '=')
          ->execute();

        // also fix the node dates
        db_update('node')
          ->fields(array(
            'created' => $message->created ? $message->created : $thread->created,
            'changed' => 0
          ))
          ->condition('nid', $newThread->nid, '=')
          ->execute();
        $first = FALSE;
      }
      else {
        $message_account = user_load_by_name($message->Username);

        $newMessage = new stdClass();
        $newMessage->nid = $newThread->nid;
        $newMessage->cid = 0;
        $newMessage->pid = 0;
        $newMessage->uid = $message_account ? $message_account->uid : 0;
        $newMessage->mail = $message_account ? $message_account->mail : '';
        $newMessage->is_anonymous = 0;
        $newMessage->homepage = '';
        $newMessage->status = COMMENT_PUBLISHED;
        $newMessage->language = LANGUAGE_NONE;
        $newMessage->subject = substr('RE: ' . $thread->title, 0, 64);
        $newMessage->comment_body[$newMessage->language][0]['value'] = $message->Message;
        $newMessage->comment_body[$newMessage->language][0]['format'] = POST_FORMAT;

        comment_submit($newMessage);
        comment_save($newMessage);

        // forces created and updated times to be NOW()
        // see SQL query for derived value
        db_update('comment')
          ->fields(array(
            'created' => $message->created,
            'hostname' => $message->IPAddress ? $message->IPAddress : '127.0.0.1',
            'changed' => 0
          ))
          ->condition('cid', $newMessage->cid, '=')
          ->execute();
      }
    }

    if ($last_updated) {
      // Update the last comment time in stats
      db_update('node_comment_statistics')
        ->fields(array(
          'last_comment_timestamp' => $last_updated
        ))
        ->condition('nid', $newThread->nid, '=')
        ->execute();
    }
  }
}

