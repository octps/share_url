<?php
  // ini_set("arg_separator.output","&");
  require_once(dirname(__FILE__) . '/../../lib/db.php');
  // 二重送信対策
  if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $token = md5(uniqid(rand(), true));
    $_SESSION['token'] = $token;
  }

  if ($_SERVER["REQUEST_METHOD"] == "GET"){
      $screen_names = user_name::get();
  } elseif
  (
      $_SERVER["REQUEST_METHOD"] == "POST"
      && (!isset($_POST['method']) && @$_POST['method'] != 'put')
  ) {
      user_name::post();
  } else {
      user_name::put();
  };

  class user_name {
    static public function get() {
      $dbh = Db::getInstance();
      try {
        $dbh->beginTransaction();
        $stmt = $dbh -> prepare ("select * from user_screen_name where id = (select screen_name_id from twitter_users where user_id = :user_id);");
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_STR);
        $stmt->execute();
        $dbh->commit();
      } catch (Exception $e) {
        $dbh->rollBack();
        echo "例外キャッチ：", $e->getMessage(), "\n";
      }
      $now_name = $stmt->fetchAll();
      $results['now_name'] = $now_name;

      try {
        $dbh->beginTransaction();
        $stmt = $dbh -> prepare ("select * from user_screen_name where user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_STR);
        $stmt->execute();
        $dbh->commit();
      } catch (Exception $e) {
        $dbh->rollBack();
        echo "例外キャッチ：", $e->getMessage(), "\n";
      }
      $names = $stmt->fetchAll();
      $results['names'] = $names;
      return $results;
    }

    static public function post() {
      unset($_SESSION['error']);
      $dbh = Db::getInstance();
      $post = $_POST;

      if (!isset($post['token'])
        || $post['token'] !== $_SESSION['token']
      ) {
        unset($_SESSION['token']);
        header("location: /404.php");
        exit;
      }

      if (!isset($post['user_screen_name'])) {
        header("location: /404.php");
        exit;        
      }
      
      $error = array();

      // 名前 文字数の確認 
      if (mb_strlen($post['user_screen_name']) < 4) {
        $error['user_screen_name'] = '名前が短すぎます';
        $_SESSION['error']['user_screen_name'] = $error['user_screen_name'];
        header("location: /members/name.php");
        exit;
      }

      // 空の名前はなしとしたい
      if ($post['user_screen_name'] == '') {
        $error['user_screen_name'] = '名前を入力してください。';
        $_SESSION['error']['user_screen_name'] = $error['user_screen_name'];
        header("location: /members/name.php");
        exit;
      }

      // 名前の重複 重複を許可する?

      // 3件までかの確認
      try {
        $dbh->beginTransaction();
        $stmt = $dbh -> prepare ("select * from user_screen_name where user_id = :user_id;");
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetchAll();
        $dbh->commit();
      } catch (Exception $e) {
        $dbh->rollBack();
        echo "例外キャッチ：", $e->getMessage(), "\n";
      }
      if (isset($results[2])) {
        $error['user_screen_name'] = 'すでに表示名が3件登録されています';
        $_SESSION['error']['user_screen_name'] = $error['user_screen_name'];
        header("location: /members/name.php");
        exit;        
      }

      // user_screen_nameの登録
      try {
        $dbh->beginTransaction();
        $stmt = $dbh -> prepare ("insert into user_screen_name (user_id, screen_name, created_at) values (:user_id, :screen_name, null);");
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_STR);
        $stmt->bindParam(':screen_name', $post['user_screen_name'], PDO::PARAM_STR);
        $stmt->execute();
        $lastInsertId = $dbh->lastInsertId('id');
        $dbh->commit();
      } catch (Exception $e) {
        $dbh->rollBack();
        echo "例外キャッチ：", $e->getMessage(), "\n";
      }

      // twitter_userにscreen_name_idの登録
      try {
        $dbh->beginTransaction();
        $stmt = $dbh -> prepare ("update twitter_users set screen_name_id = :lastInsertId where id = :user_id;");
        $stmt->bindParam(':lastInsertId', $lastInsertId, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_STR);
        $stmt->execute();
        $dbh->commit();
      } catch (Exception $e) {
        $dbh->rollBack();
        echo "例外キャッチ：", $e->getMessage(), "\n";
      }

      header("location: /members/name.php");
    }

    static public function put() {
      unset($_SESSION['error']);
      $dbh = Db::getInstance();
      $post = $_POST;

      if (!isset($post['token'])
        || $post['token'] !== $_SESSION['token']
      ) {
        unset($_SESSION['token']);
        header("location: /404.php");
        exit;
      }

      if (!isset($post['user_screen_name'])) {
        header("location: /404.php");
        exit;
      }
      
      $error = array();

      // 名前 文字数の確認 
      if (mb_strlen($post['user_screen_name']) < 4) {
        $error['user_screen_name'] = '名前が短すぎます';
        $_SESSION['error']['user_screen_name'] = $error['user_screen_name'];
        header("location: /members/name.php");
        exit;
      }

      // 空の名前はなしとしたい
      if ($post['user_screen_name'] == '') {
        $error['user_screen_name'] = '名前を入力してください。';
        $_SESSION['error']['user_screen_name'] = $error['user_screen_name'];
        header("location: /members/name.php");
        exit;
      }

      // user_screen_nameが正しいかの確認
      try {
        $dbh->beginTransaction();
        $stmt = $dbh -> prepare ("select * from user_screen_name where user_id = :user_id and screen_name = :screen_name");
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_STR);
        $stmt->bindParam(':screen_name', $post['user_screen_name'], PDO::PARAM_STR);
        $stmt->execute();
        $dbh->commit();
      } catch (Exception $e) {
        $dbh->rollBack();
        echo "例外キャッチ：", $e->getMessage(), "\n";
      }
      $results = $stmt->fetchAll();
      if (empty($results)) {
        header("location: /404.php");
        exit;
      }

      // twitter_userにscreen_name_idの登録
      try {
        $dbh->beginTransaction();
        $stmt = $dbh -> prepare ("update twitter_users set screen_name_id = :screen_name_id where id = :user_id;");
        $stmt->bindParam(':screen_name_id', $results[0]['id'], PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_STR);
        $stmt->execute();
        $dbh->commit();
      } catch (Exception $e) {
        $dbh->rollBack();
        echo "例外キャッチ：", $e->getMessage(), "\n";
      }

      header("location: /members/name.php");
    }
  }


?>
