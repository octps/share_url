<?php
require_once(dirname(__FILE__) . '/../db.php');

if ($_SERVER['REQUEST_METHOD'] === "GET") {
  $contents = members::get();
  $screen_name = $contents['screen_name'];
  unset($contents['screen_name']);
} else if ($_SERVER['REQUEST_METHOD'] === "POST") {
  members::post();
}

class members {

  public static function post() {
    session_start();

    unset($_SESSION['error']);
    unset($_SESSION['error']['url']);

    $post = $_POST;
    $back_url = "/members/?id=" . $_SESSION['user_id'];

    // バリデーション
    if (!isset($post['url']) || !isset($post['comment'])) {
      header("HTTP/1.1 404 Not Found");
      include (dirname(__FILE__) . '/../../404.php');
      exit;
    };

    $error = array();
    if ($post['url'] == "") {
      $error['url'] = "urlは必須項目です。";
    }

    if (!empty($error)) {
      $_SESSION['error']['url'] = $error['url'];
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
        header("location:$back_url");
        exit;
      }

      // titleの取得
      if (preg_match('/<title>(.*?)<\/title>/i', mb_convert_encoding($response, 'UTF-8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS'), $result)) {
        $title = $result[1];
      } else {
        $title = $url; //titleがなかったら、urlをtitleに設定
      }

      $sql = "insert into urls (url, title, created_at) values (:url, :title, null):";

      try {
        $dbh->beginTransaction();
        $stmt = $dbh -> prepare ($sql);
        $stmt->bindParam(':url', $url, PDO::PARAM_STR);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->execute();
        $url_id = $dbh->lastInsertId('id'); // $url_idを設定
        $dbh->commit();
      } catch (Exception $e) {
        $dbh->rollBack();
        echo "例外キャッチ：", $e->getMessage(), "\n";
      }
    }

    // commentのインサート
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

    header("location:$back_url");
  }

  public static function get() {
    session_start();
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

    // todo
    // ログインしていると時としていない時の条件文化
    // sessionで分岐
    // if ($_SESSION)
    // where句を適合
    $sql = "select u.id as url_id, u.url, u.title, c.id as comment_id, c.member_id, c.comment, t.id as twitter_id, t.screen_name, c.created_at from urls as u join comments as c on u.id = c.url_id join twitter_users as t on c.member_id = t.id where member_id = :member_id order by c.created_at DESC;";

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
    $results = $stmt->fetchAll();
    foreach ($results as $result) {
      // $result['opg'] = OpenGraph::fetch('$result["url"]');
      $contents[$result["url_id"]][] = $result;
    }
    $contents['screen_name'] = @$results[0]['screen_name'];
    if (empty($results)) {
      $contents['screen_name'] = $_SESSION['access_token']['screen_name'];
    }
    return $contents;
  }

}
