<?php
require_once(dirname(__FILE__) . '/../db.php');

if ($_SERVER['REQUEST_METHOD'] === "GET") {
  session_start();
  $contents = url::get();
}

class url {

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

    //quuery id 、つまりurlがいない時の処理
    // comment基準
    // commentがついていない時は存在しない
    try {
      $dbh->beginTransaction();
      $stmt = $dbh -> prepare ("select * from comments where url_id = :url_id");
      $stmt->bindParam(':url_id', $get['id'], PDO::PARAM_STR);
      $stmt->execute();
      $dbh->commit();
    } catch (Exception $e) {
      $dbh->rollBack();
      echo "例外キャッチ：", $e->getMessage(), "\n";
    }
    $urlCheck = $stmt->fetchAll();
    if (empty($urlCheck)) {
      header("HTTP/1.1 404 Not Found");
      include (dirname(__FILE__) . '/../../404.php');
      exit;
    }

    try {
      $dbh->beginTransaction();
      $stmt = $dbh -> prepare ("select u.id as url_id, u.url, u.title, c.id as comment_id, c.member_id, c.comment, t.id as twitter_id, t.screen_name, c.created_at from urls as u join comments as c on u.id = c.url_id join twitter_users as t on c.member_id = t.id where url_id = :url_id order by c.created_at DESC;");
      $stmt->bindParam(':url_id', $get['id'], PDO::PARAM_STR);
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
    return $contents;
  }

}
