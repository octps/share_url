<?php
  // ini_set("arg_separator.output","&");
  require_once(dirname(__FILE__) . '/./db.php');

  if ($_SERVER["REQUEST_METHOD"] == "GET") {
    header("locaiont:/404.php");
    exit;
  }

  url::post_validation();

  class url {
    public static function post_validation() {
      $post = $_POST;

      if (!isset($post['token'])
        || $post['token'] !== $_SESSION['token']
      ) {
          unset($_SESSION['token']);
          header("location:/404.php");
          exit;
      }

      $error = array();

      if (!isset($post['url'])
        || @$post['url'] == ""
        || (!filter_var($post['url'], FILTER_VALIDATE_URL) && preg_match('@^https?+://@i', $post['url']))
      ) {
          $error["url"] = "url_error";
      }

      if (isset($error['id'])
        || isset($error['url'])
      ) {
        $url = "/members/index.php?" . http_build_query($error);
        header("location:$url");
        exit;
      }

      url::post();

    }

    public static function post() {
      $post = $_POST;
      $dbh = Db::getInstance();

      try {
        $dbh->beginTransaction();
        $stmt = $dbh -> prepare ("insert into urls (user_id, url, created_at) values (:user_id, :url, null);");
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_STR);
        $stmt->bindParam(':url', $post['url'], PDO::PARAM_STR);
        $stmt->execute();
        $url_id = $dbh->lastInsertId('id');
        $dbh->commit();
      } catch (Exception $e) {
        $dbh->rollBack();
        echo "例外キャッチ：", $e->getMessage(), "\n";
        exit;
      }

      try {
        $dbh->beginTransaction();
        $stmt = $dbh -> prepare ("insert into comments (user_id, url_id, comment, created_at) values (:user_id, :url_id, :comment, null);");
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_STR);
        $stmt->bindParam(':url_id', $url_id, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $post['comment'], PDO::PARAM_STR);
        $stmt->execute();
        $dbh->commit();
      } catch (Exception $e) {
        $dbh->rollBack();
        echo "例外キャッチ：", $e->getMessage(), "\n";
        exit;
      }
      
      header("location: /members/index.php");

    }

  }
?>
