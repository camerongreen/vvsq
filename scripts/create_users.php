<?php

// get $old_database - standard drupal connection array
include_once("../scripts/db_details.php");

/**
 * A script to automatically add users
 *
 * It uses the app env to connect so you will need to run like this :
 * APPLICATION_ENV="development" drush php-script create_users.php > /tmp/user_create.log
 *
 * User: Cameron Green <i@camerongreen.org>
 * Date: 07/11/13
 * Time: 11:01 PM
 */

Database::addConnectionInfo('import', 'default', $old_database);
db_set_active('import');

$result = db_query('SELECT *, UNIX_TIMESTAMP(Join_date) AS signed_up FROM {userdetails}');

db_set_active();

$roles = array(
  DRUPAL_AUTHENTICATED_RID => 'authenticated user',
  5 => 'public user',
);

$admin_roles = array(
  DRUPAL_AUTHENTICATED_RID => 'authenticated user',
  5 => 'public user',
  4 => 'editor',
);

$editors = array(
  "Daisy",
  "Dark Horse",
);


// Result is returned as a iterable object that returns a stdClass object on each iteration
foreach ($result as $user) {
  if (strtolower($user->Username) == "admin") {
    continue;
  }
  $newUser = array(
    'name' => $user->Username, 
    'pass' => user_password(10), 
    'mail' => $user->Author_email, 
    'status' => $user->Active, 
    'init' => $user->Author_email,
    'created' => $user->signed_up, // see query above
    'roles' => in_array($user->Username, $editors) ? $admin_roles : $roles
  );
  $user = user_save(null, $newUser);  
  print_r($user);
}

