<?php
require_once(dirname(__FILE__) . '/../db.php');

session_start();

//ログインチェック
if (!isset($_SESSION['user_id'])) {
  unset($_SESSION['token']);
  header("location:/404.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === "GET") {
  $token = md5(uniqid(rand(), true));
  $_SESSION['token'] = $token;
  $user = me::get();
} else if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $contents = me::put();
}


class me {

  public static function get() {
    $dbh = Db::getInstance();

    // quuery id 、つまりmemberがいない時の処理
    
    try {
      $dbh->beginTransaction();
      $stmt = $dbh -> prepare ("select * from twitter_users where id = :id");
      $stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_STR);
      $stmt->execute();
      $dbh->commit();
    } catch (Exception $e) {
      $dbh->rollBack();
      echo "例外キャッチ：", $e->getMessage(), "\n";
    }
    $user = $stmt->fetchAll();

    return $user;
  }

  public static function put() {
    unset($_SESSION["error"]);
    $dbh = Db::getInstance();

    if (!isset($post['token'])
      || $post['token'] !== $_SESSION['token']
    ) {
        unset($_SESSION['token']);
        header("location:/404.php");
        exit;
    }
      
    $post = $_POST;
    if (!isset($post['name'])) {
      header("HTTP/1.1 404 Not Found");
      include (dirname(__FILE__) . '/../../404.php');
      exit;
    }
    
    $error = array();
    if (mb_strlen($post['name']) < 4) {
      $error['name'] = 'ユーザー名は4文字以上にしてください。';
      $_SESSION["error"] = $error['name'];
    }

    if (!empty($error)) {
      header("location:/members/me.php");
      exit;
    }
    try {
      $dbh->beginTransaction();
      $stmt = $dbh -> prepare ("update twitter_users set screen_name = :screen_name where id = :id");
      $stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_STR);
      $stmt->bindParam(':screen_name', $post['name'], PDO::PARAM_STR);
      $stmt->execute();
      $dbh->commit();
    } catch (Exception $e) {
      $dbh->rollBack();
      echo "例外キャッチ：", $e->getMessage(), "\n";
    }
    $_SESSION['access_token']['screen_name'] = $post['name'];
    $url = "/members/?id=" . $_SESSION['user_id'];
    header("location:$url");
  }

}
