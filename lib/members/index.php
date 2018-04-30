<?php
require_once(dirname(__FILE__) . '/../db.php');

if ($_SERVER['REQUEST_METHOD'] === "GET") {
  $contents = members::get();
  $screen_name = $contents['screen_name'];
  unset($contents['screen_name']);
}

class members {

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
