<?php

// get $old_database - standard drupal connection array
include_once("../scripts/db_details.php");

/**
 * A script to populate the Drupal Forums with entries from the old forums
 *
 * You will need to run like this :
 *  APPLICATION_ENV="development" drush php-script create_forums.php
 *
 * User: cameron
 * Date: 08/11/13
 * Time: 12:19 PM
 */

// Limit import settings
define('FORUM_MAX_TOPICS', 9999999); // only allow this many topics to be imported
define('FORUM_MAX_THREADS', 9999999); // only allow this many threads to be imported for each topic
define('FORUM_MAX_MESSAGES', 9999999); // only allow this many messages to be imported for each thread

define('FORUM_VID', 2);
define('FORUM_LOG_LINE_MAX_LENGTH', 80);
define('FORUM_POST_FORMAT', 'filtered_html');
define('FORUM_LOG_FILE', '/tmp/create_forums.log');

open_log_file();

Database::addConnectionInfo('import', 'default', $old_database);
db_set_active('import');

// only a few of these, so we'll pull into array
$result = db_query('SELECT * FROM {topic} tp WHERE tp.enabled = :active LIMIT ' . FORUM_MAX_TOPICS, array(':active' => 1));
$topics = $result->fetchAllAssoc('ID');

db_set_active();

foreach ($topics as $topic) {
  $newTopic = new stdClass();
  $newTopic->name = $topic->title;
  $newTopic->description = $topic->description;
  $newTopic->vid = FORUM_VID;
  $newTopic->parent = 0;
  taxonomy_term_save($newTopic);

  flog("Topic", $topic->title);

  db_set_active('import');
  $threads = db_query('SELECT *, UNIX_TIMESTAMP(date_created) AS created FROM {Thread} WHERE Topic_ID = :topic_id AND date_created IS NOT NULL ORDER BY created LIMIT ' . FORUM_MAX_THREADS, array(':topic_id' => $topic->ID));
  db_set_active();

  foreach ($threads as $thread) {
    $title = $thread->title ? $thread->title : "Untitled";
    flog("Thread", $title);

    $account = user_load_by_name($thread->started_by);
    $account_uid = $account ? $account->uid : 0;

    $newThread = new stdClass();
    $newThread->nid = NULL;
    $newThread->title = substr(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $title), 0, 255);
    $newThread->type = 'forum';
    node_object_prepare($newThread);

    $newThread->language = LANGUAGE_NONE;
    $newThread->body[$newThread->language][0]['summary'] = text_summary($thread->description);
    $newThread->body[$newThread->language][0]['format'] = FORUM_POST_FORMAT;
    $newThread->created = $thread->created; // see query
    $newThread->revision_timestamp = $thread->created;
    $newThread->uid = $account_uid;
    $newThread->status = 1;
    $newThread->promote = 0;
    $newThread->sticky = 0;
    $newThread->format = FORUM_POST_FORMAT;

    $newThread->taxonomy_forums[$newThread->language][0]['tid'] = $newTopic->tid;
    $newThread->comments = $thread->locked == 1 ? 1 : 2;

    db_set_active('import');
    $messages = db_query('SELECT Messages.*, userdetails.Username, UNIX_TIMESTAMP(Date_Entered) AS created FROM {Messages} JOIN {userdetails} ON User_ID = userdetails.Author_ID WHERE Thread_ID = :thread_id ORDER BY created LIMIT ' . FORUM_MAX_MESSAGES, array(':thread_id' => $thread->Id));
    db_set_active();

    // the first message in each Thread is the original post, so add that
    // to the node rather than as a comment
    $first = TRUE;
    // get last comment timestamp for comment stats table
    $last_updated = NULL;

    foreach ($messages as $message) {
      $last_updated = $message->created;
      if ($first) {
        flog("Adding content to thread node", $message->Message);
        $newThread->body[$newThread->language][0]['value'] = check_markup($message->Message, FORUM_POST_FORMAT);
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

        flog("Saving message", $message->Message);
        $newMessage = new stdClass();
        $newMessage->nid = $newThread->nid;
        //$newMessage->cid = 0;
        //$newMessage->pid = 0;
        $newMessage->uid = $message_account ? $message_account->uid : 0;
        $newMessage->mail = $message_account ? $message_account->mail : '';
        $newMessage->is_anonymous = 0;
        //$newMessage->homepage = '';
        $newMessage->status = COMMENT_PUBLISHED;
        $newMessage->language = LANGUAGE_NONE;
        $newMessage->subject = substr('RE: ' . preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $thread->title), 0, 64);
        $newMessage->comment_body[$newMessage->language][0]['value'] = check_plain($message->Message);
        $newMessage->comment_body[$newMessage->language][0]['format'] = FORUM_POST_FORMAT;

        comment_submit($newMessage);
        comment_save($newMessage);

        // Drupal forces created and updated times to be NOW() no matter what you pass in
        // so override that with the time of the post
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

fclose($GLOBALS["log_file"]);

function flog($message, $value="") {
  if ($value) {
    $value = "\t:" . substr(strip_tags(str_replace("\n", "", $value)), 0, FORUM_LOG_LINE_MAX_LENGTH);
  }
  fwrite($GLOBALS["log_file"], $message . $value . "\n");
}

function open_log_file() {
  $log_file = fopen(FORUM_LOG_FILE, "w");

  if (!$log_file) {
    die("Could not open " . FORUM_LOG_FILE);
  }

  $GLOBALS["log_file"] = $log_file;
}

