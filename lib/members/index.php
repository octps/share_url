<?php
  // ini_set("arg_separator.output","&");
  require_once(dirname(__FILE__) . '/../../lib/db.php');
  // 二重送信対策
  if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $token = md5(uniqid(rand(), true));
    $_SESSION['token'] = $token;
  }

  $dbh = Db::getInstance();

  // 現在の表示名を取得
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

  try {
    $dbh->beginTransaction();
    $stmt = $dbh -> prepare ("select u.id as url_id, c.id, c.comment, c.created_at, u.url, u.title from comments as c join urls as u on c.url_id = u.id where user_id = :user_id;");
    $stmt->bindParam(':user_id', $now_name[0]['id'], PDO::PARAM_STR);
    $stmt->execute();
    $dbh->commit();
  } catch (Exception $e) {
    $dbh->rollBack();
    echo "例外キャッチ：", $e->getMessage(), "\n";
  }
  $results = $stmt->fetchAll();
  foreach ($results as $result) {
    $contents[$result["url_id"]][] = $result;  
  }
  $user_id = @$results[0]["user_id"];
?>
