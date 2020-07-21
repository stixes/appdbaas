<?php
require('db.inc.php');
require('helpers.inc.php');

function get_root() {
  return array('error'=>false,'msg'=>'This is the frontpage. No service on this uri.');
}

function get_db($db = false) {
  if ($db!=false)
    return appdb_info($db);
  else
    return appdbs();
}

function post_db() {
  $data = $_POST;
  if (!isset($data['name']) || !isset($data['password'])) error_exit('Need name and password to create db.');
  $db = $data['name'];
  if (in_array($db,appdbs())) error_exit('Cannot create db '.$db.'. db already exists');
  create_db($db);
  error_log('create: db created');
  create_user($db,$data['password']);
  error_log('create: user created');
  grant_access($db);
  error_log('create: access granted');
  return appdb_info($db);
}

function delete_db($db = false) {
  if ($db===false) error_exit('DELETE operation requires 1 argument.');
  return query("DROP DATABASE IF EXISTS :db",array(":db" => $db)) &&
         query("DROP USER IF EXISTS ':user'@'%';",array(":user" => $db)) &&
         query("FLUSH PRIVILEGES");
}

