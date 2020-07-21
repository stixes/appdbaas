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

    try {
      $conn = new PDO("mysql:host=$db_host",$db_user,$db_password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      error_log("db-connect: success");
    } catch (PDOException $e) {
      error_log("db-connect: failed: " . $e->getMessage());
      respond(array('error'=>true,'exception'=>$e->getMessage()),500);
    }
  }
  return $conn;
}


function query($sql,$args=array()) {
  $db = db_connect();
  try {

    ## Seems prepared statement dont work well with database/user/grant statements..
    foreach ($args as $k => $a) {
      # error_log("db-query: key=$k arg=$a");
      $sql = str_replace( $k, $a, $sql);
    }

    # error_log('db-query: '.$sql);
    $query = $db->query($sql);
#    $query->execute();

    if ($query) {
      if ($query->rowCount()>0) {
        $results = $query->fetchAll();
        return $results;
      } else { return true; }
    }
  } catch (PDOException $e) {
    error_log("db-query: failed: " . $e->getMessage());
    return false;
  }
}
