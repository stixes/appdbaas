<?php
define('DB_HOST',getenv('DB_HOST'));
define('DB_USER',getenv('DB_USER'));
define('DB_PASS',getenv('DB_PASS'));

# Simple php singleton pattern
global $conn;

function db_connect() {
  global $conn;
  if (!isset($conn)) {
    $db_host = DB_HOST;
    $db_user = DB_USER;
    $db_password = DB_PASS;

    #$conn = new mysqli($db_host,$db_user,$db_password);
    #if ($conn->connect_error) {
    #  http_response_code(500);
    #  die("Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error);
    #} 
    try {
      $conn = new PDO("mysql:host=$db_host",$db_user,$db_password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      error_log("db-connect: success");
    } catch (PDOException $e) {
      error_log("db-connect: failed: " . $e->getMessage());
      http_response_code(500);
      die(json_encode(false));
    }
  }
  return $conn;
}


function query($sql,$args=array()) {
  error_log('db-exec: '.$sql);
  $db = db_connect();
  try {
    foreach ($args as $k => $a) {
#        $a = $db->real_escape_string($a);
      error_log('db-exec: key='.$k.' arg='.$a);
      $sql = str_replace( $k, $a, $sql);
    }

    error_log('db-query: '.$sql);
    $query = $db->query($sql);
    $query->execute();

    if ($query) {
      error_log(print_r($query,1));
      if ($query->rowCount()>0) {
        $results = $query->fetchAll();
        return $results;
      } else { return true; }
    }
  } catch (PDOException $e) {
    error_log("db-query: failed: " . $e->getMessage());
    http_response_code(500);
    die(json_encode(false));
  }
}
