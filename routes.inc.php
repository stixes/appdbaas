<?php
require('db.inc.php');
require('helpers.inc.php');

function get_root() {
  return array('message'=>'This is the frontpage. No service on this uri.');
}

function get_db($db = false) {
  if ($db!=false)
    return appdb_info($db);
  else
    return appdbs();
}

function post_db() {
  $data = $_POST;
  if (!isset($data['name']) || !isset($data['password'])) respond(array('error'=>true,'Need name and password to create db.'),500);
  $db = $data['name'];
  create_db($db);
  create_user($db,$data['password']);
  grant_access($db);
  return appdb_info($db);
}

function delete_db($db = false) {
  if ($db===false) respond(array('error'=>true,'DELETE operation requires 1 argument.'),500);
  if (!valid_db_name($db)) respond(array('error'=>true,'exception'=>'Invalid database name'),500);
  return query("DROP DATABASE IF EXISTS $db ;",array(":db" => $db)) &&
         query("DROP USER IF EXISTS ':user'@'%';",array(":user" => $db)) &&
         query("FLUSH PRIVILEGES");
}

