<?php
  // ini_set("arg_separator.output","&");
  require_once(dirname(__FILE__) . '/./db.php');

  if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $get = $_GET;
    if (!isset($get['id'])
      || !is_numeric($get['id'])
    ) {
      header("location:/404.php");
      exit;
    }
    $results = url::get();
  }
  elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    url::post_validation();
  }

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
      //重複チェック
      try {
        $dbh->beginTransaction();
        $stmt = $dbh -> prepare ("select id, url from urls where url = :url;");
        $stmt->bindParam(':url', $post['url'], PDO::PARAM_STR);
        $stmt->execute();
        $dbh->commit();
      } catch (Exception $e) {
        $dbh->rollBack();
        echo "例外キャッチ：", $e->getMessage(), "\n";
      }
      $results = $stmt->fetchAll();
      if (isset($results[0]['id'])) {
        $url_id = $results[0]['id'];
      } else {
        $url = $post['url'];
        $source = @file_get_contents($url);
        if (preg_match('/<title>(.*?)<\/title>/i', mb_convert_encoding($source, 'UTF-8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS'), $result)) {
          $title = $result[1];
        } else {
        //TITLEタグが存在しない場合
          $title = $url;
        }
        try {
          $dbh->beginTransaction();
          $stmt = $dbh -> prepare ("insert into urls (url, title, created_at) values (:url, :title, null);");
          $stmt->bindParam(':url', $post['url'], PDO::PARAM_STR);
          $stmt->bindParam(':title', $title, PDO::PARAM_STR);
          $stmt->execute();
          $url_id = $dbh->lastInsertId('id');
          $dbh->commit();
        } catch (Exception $e) {
          $dbh->rollBack();
          echo "例外キャッチ：", $e->getMessage(), "\n";
          exit;
        }
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

    public static function get() {
      $dbh = Db::getInstance();
      $get = $_GET;
      try {
        $dbh->beginTransaction();
        $stmt = $dbh -> prepare ("select u.id, u.url, u.title, c.comment from urls as u join comments as c on u.id = c.url_id where c.url_id = :url_id ");
        $stmt->bindParam(':url_id', $get['id'], PDO::PARAM_STR);
        $stmt->execute();
        $dbh->commit();
      } catch (Exception $e) {
        $dbh->rollBack();
        echo "例外キャッチ：", $e->getMessage(), "\n";
      }
      $results = $stmt->fetchAll();
      return $results;
    }

  }
?>
