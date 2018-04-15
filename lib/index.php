<?php
  require_once(dirname(__FILE__) . '/../lib/db.php');
  require_once(dirname(__FILE__) . '/../lib/OpenGraph.php');

  session_start();

  if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $contents = index::get();
    // 二重送信対策
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
      $token = md5(uniqid(rand(), true));
      $_SESSION['token'] = $token;
    }
  }
  elseif ($_SERVER['REQUEST_METHOD'] === "POST") {
    $contents = index::post();
  }

  class index {

    public static function get() {
      $dbh = Db::getInstance();

      try {
        $dbh->beginTransaction();
        $stmt = $dbh -> prepare ("select u.id, u.url, u.title, h.user_id, h.hash from urls as u join hashs as h on u.id = h.url_id order by h.updated_at DESC");
        $stmt->execute();
        $dbh->commit();
      } catch (Exception $e) {
        $dbh->rollBack();
        echo "例外キャッチ：", $e->getMessage(), "\n";
      }
      $results = $stmt->fetchAll();
      foreach ($results as $result) {
        $result['opg'] = OpenGraph::fetch('$result["url"]');
        $contents[$result["id"]][] = $result;
      }

      return $contents;
    }

    public static function post() {

      $post = $_POST;

      // バリティーションなど
      if (!isset($post['token'])
        || $post['token'] !== $_SESSION['token']
      ) {
          unset($_SESSION['token']);
          header("location:/404.php");
          exit;
      }

      // トリップのバリディーションなど
      if (!isset($post['trip'])) {
          unset($_SESSION['token']);
          header("location:/404.php");
          exit;        
      }

      // if ($post['trip'] !== '') {
      //     $trip = password_hash($post['trip'], PASSWORD_DEFAULT);
      //     //あるか確認 あったら そのidを返す
      //     $dbh = Db::getInstance();
      //     try {
      //       $dbh->beginTransaction();
      //       $stmt = $dbh -> prepare ("select * from users where password = :password;");
      //       $stmt->bindParam(':password', $url, PDO::PARAM_STR);
      //       $stmt->execute();
      //       $dbh->commit();
      //     } catch (Exception $e) {
      //       $dbh->rollBack();
      //       echo "例外キャッチ：", $e->getMessage(), "\n";
      //     }

      //     //なかったらinsert そのidを返す
      // }

      // urlとハッシュの分解
      $urls_array = explode(" ", $post['urls']);
      $url_array = preg_grep('@^https?+://@i',$urls_array); // hrlの取得
      $url = $url_array[0];
      $hashs = preg_grep('/^[#＃][Ａ-Ｚａ-ｚA-Za-z一-鿆0-9０-９ぁ-ヶｦ-ﾟー]+/', $urls_array); // hashの取得(複数)
      if (empty($hashs)) {
        $hashs = array('#no_hash');
      }
      $error = array();

      if (!isset($url)
        || @$url == ""
        || (!filter_var($url, FILTER_VALIDATE_URL) && preg_match('@^https?+://@i', $url))
      ) {
          $error["url"] = "url_error";
      }

      if (isset($error['url'])) {
        $return_url = "/members/index.php?" . http_build_query($error);
        header("location:$return_url");
        exit;
      }

      // insert
      $dbh = Db::getInstance();
      //重複チェック
      try {
        $dbh->beginTransaction();
        $stmt = $dbh -> prepare ("select id, url from urls where url = :url;");
        $stmt->bindParam(':url', $url, PDO::PARAM_STR);
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
          $stmt->bindParam(':url', $url, PDO::PARAM_STR);
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
        $user_id = NULL;
        // $user_idがある場合の処理
        // $user_idを置き換える
        // if ( )
        foreach(@$hashs ?: array() as $hash) {
          $dbh->beginTransaction();
          $stmt = $dbh -> prepare ("insert into hashs (url_id, user_id, hash, created_at) values (:url_id, :user_id, :hash, null);");
          $stmt->bindParam(':url_id', $url_id, PDO::PARAM_STR);
          $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
          $stmt->bindParam(':hash', $hash, PDO::PARAM_STR);
          $stmt->execute();
          $dbh->commit();
        }
      } catch (Exception $e) {
        $dbh->rollBack();
        echo "例外キャッチ：", $e->getMessage(), "\n";
        exit;
      }
      header("location:/");
    }

  }

?>
