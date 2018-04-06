<?php
  ini_set("arg_separator.output","&");
  require_once(dirname(__FILE__) . '/../lib/db.php');
  // 二重送信対策
  session_start();
  if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $token = md5(uniqid(rand(), true));
    $_SESSION['token'] = $token;
    confirm::check();
  }

  class confirm {
    public static function check() {
      $get = $_GET;
      $error = array();
      if (!isset($get['confirm'])
        || !isset($get['hash'])
        || ($get['confirm'] !== "true")
      ) {
        $error["confirm"] = "error";
        $url = "/confirm.php?" . http_build_query($error);
        header("location:$url");
        exit;
      }

      $now = new DateTime();
      $limit = $now->modify("-1 days");
      $limitDatetimeString = $limit->format('Y-m-d h:m:s');
      $dbh = Db::getInstance();
      $stmt = $dbh -> prepare ("select * from users where confirm_url = :confirm_url AND created_at > :created_at");
      $stmt->bindParam(':confirm_url', $get['hash'], PDO::PARAM_STR);
      $stmt->bindParam(':created_at', $limitDatetimeString, PDO::PARAM_STR);
      $stmt->execute();
      $result = $stmt->fetchAll();
      if (empty($result)) {
        $error["confirm"] = "error";
        $url = "/confirm.php?" . http_build_query($error);
        header("location:$url");
        exit;
      }
      $id = $result[0]['id'];
      $stmt = $dbh -> prepare ("update users set confirm = 1 where id = :id");
      $stmt->bindParam(':id', $id, PDO::PARAM_STR);
      $stmt->execute();
      header("location:/login.php");
    }
  }

?>
