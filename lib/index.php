<?php
require_once(dirname(__FILE__) . '/./db.php');

session_start();

if ($_SERVER['REQUEST_METHOD'] === "GET") {
  $token = md5(uniqid(rand(), true));
  $_SESSION['token'] = $token;
  $contents = root::get();
}

class root {

  public static function get() {
    $dbh = Db::getInstance();
    try {
      $dbh->beginTransaction();
      $stmt = $dbh -> prepare ("select u.id as url_id, u.url,  u.title, c.id as comment_id,  c.member_id, c.comment, t.id as twitter_id, t.screen_name, c.created_at from urls as u join comments as c on u.id = c.url_id join twitter_users as t on c.member_id = t.id order by c.created_at DESC;");
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
