<?php
require_once(dirname(__FILE__) . '/../db.php');

session_start();

if ($_SERVER['REQUEST_METHOD'] === "GET") {
  $token = md5(uniqid(rand(), true));
  $_SESSION['token'] = $token;
  $contents = members::get();
  $screen_name = $contents['screen_name'];
  $follow_id = $contents['follow_id'];
  unset($contents['screen_name']);
  unset($contents['follow_id']);
} else if ($_SERVER['REQUEST_METHOD'] === "POST" && !isset($_POST['method']) ) {
  members::post();
} else if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['method']) && $_POST['method'] == "delete" ) {
  members::delete();
}

class members {

  public static function delete() {
    $post = $_POST;
    $back_url = "/members/?id=" . $_SESSION['user_id'];
    if (!isset($post['comment_id']) || !isset($post['method'])) {
      header("HTTP/1.1 404 Not Found");
      include (dirname(__FILE__) . '/../../404.php');
      exit;
    };

    if (!isset($post['token'])
      || $post['token'] !== $_SESSION['token']
    ) {
        unset($_SESSION['token']);
        header("location:/404.php");
        exit;
    }

    $dbh = Db::getInstance();
    $sql = "delete from comments where id = :id AND member_id = :member_id";

    try {
      $dbh->beginTransaction();
      $stmt = $dbh -> prepare ($sql);
      $stmt->bindParam(':id', $post['comment_id'], PDO::PARAM_INT);
      $stmt->bindParam(':member_id', $_SESSION['user_id'], PDO::PARAM_INT);
      $stmt->execute();
      $dbh->commit();
    } catch (Exception $e) {
      $dbh->rollBack();
      echo "例外キャッチ：", $e->getMessage(), "\n";
    }

    header("location:$back_url");
  }

  public static function post() {
    unset($_SESSION['error']);
    unset($_SESSION['error']['url']);
    unset($_SESSION['post']);
    unset($_SESSION['post']['url']);
    unset($_SESSION['post']['comment']);

    $post = $_POST;
    $back_url = "/members/?id=" . $_SESSION['user_id'];

    // バリデーション
    if (!isset($post['url']) || !isset($post['comment'])) {
      header("HTTP/1.1 404 Not Found");
      include (dirname(__FILE__) . '/../../404.php');
      exit;
    };

    if (!isset($post['token'])
      || $post['token'] !== $_SESSION['token']
    ) {
        unset($_SESSION['token']);
        header("location:/404.php");
        exit;
    }

    $error = array();
    if ($post['url'] == "") {
      $error['url'] = "urlは必須項目です。";
    }

    if (!empty($error)) {
      $_SESSION['error']['url'] = $error['url'];
      $_SESSION['post']['url'] = $post['url'];
      $_SESSION['post']['comment'] = $post['comment'];
      header("location:$back_url");
      exit;
    }

    if (filter_var($post['url'], FILTER_VALIDATE_URL)
      && preg_match('|^https?://.*$|', $post['url']))
    {
      $url = $post['url'];
    } else {
      $error['url'] = "urlの形式が正しくありません。";
      $_SESSION['error']['url'] = $error['url'];
      $_SESSION['post']['url'] = $post['url'];
      $_SESSION['post']['comment'] = $post['comment'];
      header("location:$back_url");
      exit;
    }

    // commentが空だったら...を入れる
    if ($post['comment'] == "") {
      $post['comment'] = "...";
    }

    // DB登録
    $dbh = Db::getInstance();

    //urlがあるか確認
    $url_insert_flag = false;
    $sql = "select * from urls where url = :url";

    try {
      $dbh->beginTransaction();
      $stmt = $dbh -> prepare ($sql);
      $stmt->bindParam(':url', $url, PDO::PARAM_STR);
      $stmt->execute();
      $dbh->commit();
    } catch (Exception $e) {
      $dbh->rollBack();
      echo "例外キャッチ：", $e->getMessage(), "\n";
    }
    $url_array = $stmt->fetchAll();
    if (!empty($url_array)) {
      $url_id = $url_array[0]['id']; // $url_idを設定
    } else {
      $url_insert_flag = true;
    }

    // url フラグがtrueだったらinsert
    if ($url_insert_flag === true) {

      // file_get_contetsでstatuscodeの確認
      $context = stream_context_create(array(
        'http' => array('ignore_errors' => true)
      ));
      $response = @file_get_contents($url, false, $context);

      preg_match('/HTTP\/1\.[0|1|x] ([0-9]{3})/', $http_response_header[0], $matches);
      $status_code = $matches[1];
      if ($status_code != '200') {
        $error['url'] = "urlが正しく取得できませんでした。";
        $_SESSION['error']['url'] = $error['url'];
        $_SESSION['post']['url'] = $post['url'];
        $_SESSION['post']['comment'] = $post['comment'];
        header("location:$back_url");
        exit;
      }

      // 形式の確認
      $type = "html";
      $type_check = @exif_imagetype($url);
      if ($type_check == "1" || $type_check == "2" || $type_check == "3" ) {
        $type = "image";
        $title = $url;
      }

      if ($type === "html") {
        // titleの取得
        if (preg_match('/<title>(.*?)<\/title>/i', mb_convert_encoding($response, 'UTF-8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS'), $result)) {
          $title = $result[1];
        } else {
          $title = $url; //titleがなかったら、urlをtitleに設定
        }
      }

      $sql = "insert into urls (url, title, type, created_at) values (:url, :title, :type, null);";

      try {
        $dbh->beginTransaction();
        $stmt = $dbh -> prepare ($sql);
        $stmt->bindParam(':url', $url, PDO::PARAM_STR);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->execute();
        $url_id = $dbh->lastInsertId('id'); // $url_idを設定
        $dbh->commit();
      } catch (Exception $e) {
        $dbh->rollBack();
        echo "例外キャッチ：", $e->getMessage(), "\n";
      }
    } else { // falseだったらupdate
      $sql = "update urls set updated_at = null where id = :id;";

      try {
        $dbh->beginTransaction();
        $stmt = $dbh -> prepare ($sql);
        $stmt->bindParam(':id', $url_id, PDO::PARAM_STR);
        $stmt->execute();
        $dbh->commit();
      } catch (Exception $e) {
        $dbh->rollBack();
        echo "例外キャッチ：", $e->getMessage(), "\n";
      }

    }

    // commentのインサート
    if ($url_id != 0) {
      $sql = "insert into comments (member_id, url_id, comment, created_at) values (:member_id, :url_id, :comment, null);";
      try {
        $dbh->beginTransaction();
        $stmt = $dbh -> prepare ($sql);
        $stmt->bindParam(':member_id', $_SESSION['user_id'], PDO::PARAM_STR);
        $stmt->bindParam(':url_id', $url_id, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $post['comment'], PDO::PARAM_STR);
        $stmt->execute();
        $dbh->commit();
      } catch (Exception $e) {
        $dbh->rollBack();
        echo "例外キャッチ：", $e->getMessage(), "\n";
      }
    }

    header("location:$back_url");
  }

  public static function get() {
    $get = $_GET;
    $dbh = Db::getInstance();

    if (!isset($get['id'])
      || !is_numeric($get['id'])
      || $get['id'] < 1001)
    {
      header("HTTP/1.1 404 Not Found");
      include (dirname(__FILE__) . '/../../404.php');
      exit;
    };

    // quuery id 、つまりmemberがいない時の処理
    
    try {
      $dbh->beginTransaction();
      $stmt = $dbh -> prepare ("select * from twitter_users where id = :id");
      $stmt->bindParam(':id', $get['id'], PDO::PARAM_STR);
      $stmt->execute();
      $dbh->commit();
    } catch (Exception $e) {
      $dbh->rollBack();
      echo "例外キャッチ：", $e->getMessage(), "\n";
    }
    $memberCheck = $stmt->fetchAll();
    if (empty($memberCheck)) {
      header("HTTP/1.1 404 Not Found");
      include (dirname(__FILE__) . '/../../404.php');
      exit;
    }

    $page = @$get['page'];
    if (!isset($get['page']) || !is_numeric($get["page"]) || @$get['page'] == false) {
      $page = 0;
    }

    // todo
    // ログインしていると時としていない時の条件文化
    // ログインしている時は、followerの情報が同時に表示される
    // sessionで分岐
    // if ($_SESSION)
    // where句を適合

    // ログインしていて自分のページの時
    $sql_where = "where (member_id = :member_id";

    if ($get['id'] == @$_SESSION['user_id']) {
      try {
        $dbh->beginTransaction();
        $stmt = $dbh -> prepare ("select * from follows where member_id = :member_id");
        $stmt->bindParam(':member_id', $_SESSION['user_id'], PDO::PARAM_STR);
        $stmt->execute();
        $dbh->commit();
      } catch (Exception $e) {
        $dbh->rollBack();
        echo "例外キャッチ：", $e->getMessage(), "\n";
      }
      $followers = $stmt->fetchAll();
      foreach (@$followers ?: array() as $follower) {
        $sql_where .= " OR member_id = " . $follower['follows_member_id'];
      }
    }
    $sql_where .= ")";

    $limit = 8;
    $offset = $limit * $page;

    // urlを取得
    $sql_1 = "select distinct u.id, u.url, u.title, u.type from urls as u join comments as c on u.id = c.url_id "; 
    $sql_2 = $sql_where;
    $sql_3 = " order by u.updated_at DESC limit :offset, :limit;";
    $sql = $sql_1 . $sql_2 . $sql_3;

    try {
      $dbh->beginTransaction();
      $stmt = $dbh -> prepare ($sql);
      $stmt->bindParam(':member_id', $get['id'], PDO::PARAM_STR);
      $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
      $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
      $stmt->execute();
      $dbh->commit();
    } catch (Exception $e) {
      $dbh->rollBack();
      echo "例外キャッチ：", $e->getMessage(), "\n";
    }
    $url_results = $stmt->fetchAll();
    $sql_where_urls = "";
    foreach (@$url_results ?: array() as $url_result) {
      $sql_where_urls .= "OR url_id = " . $url_result["id"] . " ";
    }
    $sql_where_urls = ltrim($sql_where_urls, 'OR');
    $sql_where_urls = "(" . $sql_where_urls . ")";


    if (!empty($url_results)) {
      $sql_1 = "select c.id as commet_id, t.screen_name, c.comment, c.member_id, c.url_id, c.created_at, u.id from comments as c join urls as u on c.url_id = u.id join twitter_users as t on c.member_id = t.id ";
      $sql_2 = $sql_where;
      $sql_3 = " AND ";
      $sql_4 = $sql_where_urls;
      $sql_5 = " order by c.created_at DESC;";

      $sql = $sql_1 . $sql_2 . $sql_3 . $sql_4 . $sql_5;

      try {
        $dbh->beginTransaction();
        $stmt = $dbh -> prepare ($sql);
        $stmt->bindParam(':member_id', $get['id'], PDO::PARAM_STR);
        $stmt->execute();
        $dbh->commit();
      } catch (Exception $e) {
        $dbh->rollBack();
        echo "例外キャッチ：", $e->getMessage(), "\n";
      }
      $commets_results = $stmt->fetchAll();
      foreach($commets_results as $commets_result) {
        $commets_result_array[$commets_result['url_id']][] = $commets_result;
      }
    }

    $contents["urls"] = $url_results;
    $contents["comments"] = @$commets_result_array;

    // foreach ($results as $result) {
    //   print_r($result);
    //   $sql_comments = "select * from comments where url_id = :url_id;";
    //   try {
    //     $dbh->beginTransaction();
    //     $stmt = $dbh -> prepare ($sql);
    //     $stmt->bindParam(':url_id', $result['id'], PDO::PARAM_STR);
    //     $stmt->execute();
    //     $dbh->commit();
    //   } catch (Exception $e) {
    //     $dbh->rollBack();
    //     echo "例外キャッチ：", $e->getMessage(), "\n";
    //   }
    //   $results_comments = $stmt->fetchAll();
    //   $contents[$result["url_id"]][] = $result;
    //   $contents[$result["url_id"]]["comments"] = $results_comments;
    // }

    //
    // $sql_1 = "select u.id as url_id, u.url, u.title, u.type, c.id as comment_id, c.member_id, c.comment, t.id as twitter_id, t.screen_name, c.created_at from urls as u join comments as c on u.id = c.url_id join twitter_users as t on c.member_id = t.id ";
    // $sql_2 = $sql_where;
    // $sql_3 = " order by c.created_at DESC limit :offset, :limit;";
    // $sql = $sql_1 . $sql_2 . $sql_3;

    // try {
    //   $dbh->beginTransaction();
    //   $stmt = $dbh -> prepare ($sql);
    //   $stmt->bindParam(':member_id', $get['id'], PDO::PARAM_STR);
    //   $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    //   $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    //   $stmt->execute();
    //   $dbh->commit();
    // } catch (Exception $e) {
    //   $dbh->rollBack();
    //   echo "例外キャッチ：", $e->getMessage(), "\n";
    // }
    // $results = $stmt->fetchAll();
    // foreach ($results as $result) {
    //   // $result['opg'] = OpenGraph::fetch('$result["url"]');
    //   $contents[$result["url_id"]][] = $result;
    // }

    //ユーザー名の取得
    $sql = "select * from twitter_users where id = :id;";

    try {
      $dbh->beginTransaction();
      $stmt = $dbh -> prepare ($sql);
      $stmt->bindParam(':id', $get['id'], PDO::PARAM_STR);
      $stmt->execute();
      $dbh->commit();
    } catch (Exception $e) {
      $dbh->rollBack();
      echo "例外キャッチ：", $e->getMessage(), "\n";
    }
    $member_results = $stmt->fetchAll();

    $contents['screen_name'] = @$member_results[0]['screen_name'];
    $contents['follow_id'] = @$member_results[0]['id'];

    return $contents;
  }

}
