<?php

/**
 * A script to export users from the old system to Drupal
 *
 * It uses the app env to connect so you will need to do this first:
 * export APPLICATION_ENV="development" 
 *
 * User: Cameron <i@camerongreen.org>
 * Date: 07/11/13
 * Time: 11:01 PM
 */
$old_database = array(
  'database' => '',
  'username' => '', 
  'password' => '',
  'host' => '',
  'driver' => '',
);

Database::addConnectionInfo('vvsq_import', 'default', $old_database);
db_set_active($old_database['database']);

$result = db_query('SELECT *, UNIX_TIMESTAMP(Join_date) AS signed_up FROM {userdetails} ud WHERE ud.Active = :active', array(':active' => 1));

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

// list of editors usernames
$editors = array(
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
    'status' => 1, 
    'init' => $user->Author_email,
    'created' => $user->signed_up, // see query above
    'roles' => in_array($user->Username, $editors) ? $admin_roles : $roles
  );
  $user = user_save(null, $newUser);  
  print_r($user);
}

