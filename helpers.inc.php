<?php
function create_user($name,$password) {
  $sql = "CREATE USER IF NOT EXISTS ':name'@'%' IDENTIFIED BY ':password';";
  query($sql,array(":name"=>$name,":password"=>$password));
}

function create_db($name) {
  $sql = "CREATE DATABASE IF NOT EXISTS :name ";
  query($sql,array(":name" => $name));
}

function grant_access($name) {
  $sql = "GRANT ALL PRIVILEGES ON :db . * TO ':user'@'%';";
  query($sql,array(":db" => $name,":user" => $name));
  $sql = "FLUSH PRIVILEGES;";
  query($sql);
}

function appdb_info($db) {
  if (!in_array($db,appdbs())) {
    http_response_code(404);
    return array('name'=>$db, 'exists'=>false);
  }
  return array('name'=>$db, 'exists'=>true,'has_user'=>true);

}

function appdbs() {
  $dbs=query('show databases;');
  $users=query('select distinct user from mysql.user where not (user="root" or user like "%.sys");');
  if ($dbs && $users) {
    if (is_array($dbs) && is_array($users)) {
      return array_values(array_intersect(array_column($dbs,'Database'),array_column($users,'User')));
    } else {
      return array();
    }
  } else {
    http_response_code(500);
    return false;
  }
}
