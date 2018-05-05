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
  $contents = follow::get();
} else if ($_SERVER['REQUEST_METHOD'] === "POST" && !isset($_POST['unfollow'])) {
  follow::post();
} else if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['unfollow'])) {
  follow::delete();
}


class follow {

  public static function delete() {
    $dbh = Db::getInstance();

    $post = $_POST;
    if (!isset($post['token'])
      || $post['token'] !== $_SESSION['token']
    ) {
        unset($_SESSION['token']);
        header("location:/404.php");
        exit;
    }
    if (!isset($post['follow_id']) || !is_numeric($post['follow_id']) || $post['follow_id'] < 1001) {
      header("HTTP/1.1 404 Not Found");
      include (dirname(__FILE__) . '/../../404.php');
      exit;
    }

    try {
      $dbh->beginTransaction();
      $stmt = $dbh -> prepare ("delete from follows where member_id = :member_id AND follows_member_id = :follows_member_id;");
      $stmt->bindParam(':member_id', $_SESSION['user_id'], PDO::PARAM_STR);
      $stmt->bindParam(':follows_member_id', $post['follow_id'], PDO::PARAM_STR);
      $stmt->execute();
      $dbh->commit();
    } catch (Exception $e) {
      $dbh->rollBack();
      echo "例外キャッチ：", $e->getMessage(), "\n";
    }
    $url = "/members/?id=" . $_SESSION['user_id'];
    header("location:$url");
  }

  public static function post() {
    $dbh = Db::getInstance();

    $post = $_POST;
    if (!isset($post['token'])
      || $post['token'] !== $_SESSION['token']
    ) {
        unset($_SESSION['token']);
        header("location:/404.php");
        exit;
    }

    if (!isset($post['follow_id']) || !is_numeric($post['follow_id']) || $post['follow_id'] < 1001) {
      header("HTTP/1.1 404 Not Found");
      include (dirname(__FILE__) . '/../../404.php');
      exit;
    }

    // 重複確認が必要
    try {
      $dbh->beginTransaction();
      $stmt = $dbh -> prepare ("select * from followers where member_id = :member_id AND follows_member_id = :follows_member_id;");
      $stmt->bindParam(':member_id', $_SESSION['user_id'], PDO::PARAM_STR);
      $stmt->bindParam(':follows_member_id', $post['follow_id'], PDO::PARAM_STR);
      $stmt->execute();
      $dbh->commit();
    } catch (Exception $e) {
      $dbh->rollBack();
      echo "例外キャッチ：", $e->getMessage(), "\n";
    }
    $followers_check = $stmt->fetchAll();
    if (!empty($followers) {
        header("location:/404.php");
        exit;
    }


    try {
      $dbh->beginTransaction();
      $stmt = $dbh -> prepare ("insert into follows (member_id, follows_member_id, created_at) values (:member_id, :follows_member_id, null);");
      $stmt->bindParam(':member_id', $_SESSION['user_id'], PDO::PARAM_STR);
      $stmt->bindParam(':follows_member_id', $post['follow_id'], PDO::PARAM_STR);
      $stmt->execute();
      $dbh->commit();
    } catch (Exception $e) {
      $dbh->rollBack();
      echo "例外キャッチ：", $e->getMessage(), "\n";
    }
    $url = "/members/?id=" . $_SESSION['user_id'];
    header("location:$url");
  }

  public static function get() {
    $dbh = Db::getInstance();
  
    try {
      $dbh->beginTransaction();
      $stmt = $dbh -> prepare ("select f.follows_member_id, t.id, t.screen_name from follows as f join twitter_users as t on f.follows_member_id = t.id where f.member_id = :id");
      $stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_STR);
      $stmt->execute();
      $dbh->commit();
    } catch (Exception $e) {
      $dbh->rollBack();
      echo "例外キャッチ：", $e->getMessage(), "\n";
    }
    $followers = $stmt->fetchAll();

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
    $me = $stmt->fetchAll();

    $contents['followers'] = $followers;
    $contents['me'] = $me;
    return $contents;
  }

}
