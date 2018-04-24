<?php
  // ini_set("arg_separator.output","&");
  require_once(dirname(__FILE__) . '/../../lib/db.php');
  // 二重送信対策

  $dbh = Db::getInstance();

  $get = $_GET;
  if (!isset($get['user']) || !is_numeric($get['user'])) {
    header('location:/404.php');
    exit;
  }
  // 現在の表示名を取得
  try {
    $dbh->beginTransaction();
    $stmt = $dbh -> prepare ("select * from comments as c join urls as u on c.url_id = u.id JOIN user_screen_name as usn  on usn.id = c.user_id where c.user_id = :user_id;");
    $stmt->bindParam(':user_id', $get['user'], PDO::PARAM_STR);
    $stmt->execute();
    $dbh->commit();
  } catch (Exception $e) {
    $dbh->rollBack();
    echo "例外キャッチ：", $e->getMessage(), "\n";
  }
  $results = $stmt->fetchAll();

  $screen_name = $results[0]['screen_name'];

  $contents = array();
  foreach($results as $result) {
    $contents[$result['url_id']][] = $result;
  }
?>
