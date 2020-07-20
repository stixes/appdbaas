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

    $conn = new mysqli($db_host,$db_user,$db_password);
    if ($conn->connect_errno) {
      http_response_code(500);
      die("Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error);
    } 
  }
  return $conn;
}


function query($sql,$args=false) {
  error_log('db-exec: '.$sql);
  $db = db_connect();
#  if ($args!==false && !is_array($args)) $args=array($args);

#  if ($stmt = $db->prepare($sql)) {
#    if (is_array($args)) 
#      foreach ($args as $a) {
#        error_log('db-exec: arg='.$a);
#        $stmt->bind_param("s",$a);
#      }
#    error_log(print_r($stmt,1));
#    $stmt->execute();
#    $query = $stmt->get_result();

    if (is_array($args)) 
      foreach ($args as $k => $a) {
        $a = $db->real_escape_string($a);
        error_log('db-exec: key='.$k.' arg='.$a);
        $sql = str_replace( $k, $a, $sql);
      }

    $query = $db->query($sql);

    if ($query) {
      if ($query->num_rows>0) {
        $results = array();
        while ($row = $query->fetch_assoc()) $results[]= $row;
        return $results;
      } else { return true; }
    }
#  }
  error_log('db: error during query: '.$sql);
  error_log(' - '.$db->error);
  return false;
}
