<?php
  // ini_set("arg_separator.output","&");
  require_once(dirname(__FILE__) . '/../../lib/db.php');
  // 二重送信対策
  if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $token = md5(uniqid(rand(), true));
    $_SESSION['token'] = $token;
  }
?>
