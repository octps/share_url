<?php

  checklogin::get();

  class checklogin {
    public static function get() {
      session_start();
      print_r($_SESSION['access_token']);
      if (!isset($_SESSION["user_id"]) || !is_numeric($_SESSION["user_id"])) {
        header("location:/index.php");
      }

    }

  }
?>
