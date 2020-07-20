<?php
require('routes.inc.php');

$uri = explode('/',$_SERVER['REQUEST_URI']);
$method = $_SERVER['REQUEST_METHOD'];
$data = $_POST;

error_log("$method - ".$_SERVER['REQUEST_URI']);

##---- Router logic ----##
if ($uri[1]=='')
  $func=strtolower($method).'_root';
else
  $func=strtolower($method).'_'.$uri[1];
if (function_exists($func)) {
  array_shift($uri);array_shift($uri);
  $msg = call_user_func_array($func,$uri);
  die(json_encode($msg));
}
http_response_code(500);
die('Unsupported function not found');
