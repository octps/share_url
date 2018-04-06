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
    signin::input_validate_error();
    signin::sql_validate_error();
    signin::sendmail();
  }


  class signin {
    public static function input_validate_error() {
      $post = $_POST;
      $error = array();
      if (!isset($post['name'])
        || @$post['name'] == ""
        || strlen(@$post['name']) < 3
        || strlen(@$post['name']) > 21
      ) {
        $error["name"] = "name error";
      }

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

      if (isset($error['name'])
        || isset($error['email'])
        || isset($error['password'])
        || isset($error['token'])
      ) {
        $url = "/signin.php?" . http_build_query($error);
        header("location:$url");
        exit;
      }
    }

    public static function sql_validate_error() {
      $post = $_POST;
      $error = array();

      $dbh = Db::getInstance();
      $stmt = $dbh -> prepare ("select * from users where email = :email");
      $stmt->bindParam(':email', $post['email'], PDO::PARAM_STR);
      $stmt->execute();
      $result = $stmt->fetchAll();
      if (!empty($result)) {
        $error["member"] = "yet member";
        $url = "/signin.php?" . http_build_query($error);
        header("location:$url");
        exit;
      }
    }

    public static function sendmail() {
      $post = $_POST;
      $password = password_hash($post['password'], PASSWORD_DEFAULT);
      $confirm_url = md5(uniqid(rand(), true));

      try {
        $dbh = Db::getInstance();
        $stmt = $dbh -> prepare ("insert into users (name, email, password, confirm, confirm_url, created_at) values (:name, :email, :password, '0', :confirm_url, NULL);");
        $stmt->bindParam(':name', $post['name'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $post['email'], PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':confirm_url', $confirm_url, PDO::PARAM_STR);
        $stmt->execute();
      } catch (Exception $e) {
        echo "例外キャッチ：", $e->getMessage(), "\n";
        exit;
      }

      //メールの送信処理
      $url = "http://localhost/complete.php?hash=" . $confirm_url . "&confirm=true";

      mb_language("Japanese");
      mb_internal_encoding("UTF-8");

      $subject_origin = '登録ありがとうございます';
      $message_origin = '登録ありがとうございます。以下のurlに24時間以内にアクセスすることで正式に登録となります。\n このメールに心当たりがない形は上記urlにアクセスしないでください。\n' . $url;

      $to = $post['email'];
      $subject = $subject_origin;
      $message = $message_origin;
      $headers = 'From: renmo9@gmail.com' . "\r\n";

      mb_send_mail($to, $subject, $message, $headers); 

      //
      header("location:/confirm.php");

    }

  }
?>
