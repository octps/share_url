<?php
  ini_set("arg_separator.output","&");
  require_once(dirname(__FILE__) . '/../lib/db.php');
  // 二重送信対策
  session_start();
  if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $token = md5(uniqid(rand(), true));
    $_SESSION['token'] = $token;

    //getクエリ取得 エラー処理用
    $get = $_GET;
  }


  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    login::input_validate_error();
    login::sql_validate_error();
  }

  class login {
    public static function input_validate_error() {
      $post = $_POST;
      $_SESSION['email'] = $post['email'];
      $error = array();
      if (!isset($post['email'])
        || @$post['email'] == ""
        || !filter_var(@$post['email'], FILTER_VALIDATE_EMAIL)
      ) {
        $error["email"] = "email_error";
      }

      if (!isset($post['password'])
        || @$post['password'] == ""
        || strlen(@$post['password']) < 8
        || !preg_match("/^[a-zA-Z0-9]+$/", $post['password'])
      ) {
          $error["password"] = "password_error";
      }

      if (!isset($post['token'])
        || $post['token'] !== $_SESSION['token']
      ) {
        $error["token"] = "token error";
      }
      unset($_SESSION['token']);

      if (isset($error['email'])
        || isset($error['password'])
        || isset($error['token'])
      ) {
        $url = "/login.php?" . http_build_query($error);
        header("location:$url");
        exit;
      }
    }

    public static function sql_validate_error() {
      $post = $_POST;
      $error = array();

      $dbh = Db::getInstance();
      $stmt = $dbh -> prepare ("select * from users where email = :email AND confirm > 0");
      $stmt->bindParam(':email', $post['email'], PDO::PARAM_STR);
      $stmt->execute();
      $result = $stmt->fetchAll();
      if (empty($result)) {
        $error["member"] = "not member";
        $url = "/login.php?" . http_build_query($error);
        header("location:$url");
        exit;
      }
      
      $hash = password_verify($post['password'], $result[0]['password']);
      if ($hash) {
        session_regenerate_id(TRUE);
        $_SESSION['user_id'] = $result[0]["id"];
        $_SESSION['user_name'] = $result[0]["name"];
      }
      header("location: /members/index.php");

    }

  }
?>
