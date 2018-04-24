<?php
  require_once(dirname(__FILE__) . '/../lib/db.php');

  session_start();
  // if (isset($_SESSION["user_id"]) && is_numeric($_SESSION["user_id"])) {
  //     header("location:/members/index.php");
  // }
  $dbh = Db::getInstance();

  try {
    $dbh->beginTransaction();
    $stmt = $dbh -> prepare ("select u.id as url_id, c.id, c.comment, c.created_at, u.url, u.title from comments as c join urls as u on c.url_id = u.id order by c.created_at DESC");
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

?>
