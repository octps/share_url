<?php
  require_once(dirname(__FILE__) . '/../lib/db.php');

  session_start();
  if (isset($_SESSION["user_id"]) && is_numeric($_SESSION["user_id"])) {
      header("location:/members/index.php");
  }

?>
