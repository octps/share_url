<?php
  require_once(dirname(__FILE__) . '/../lib/db.php');

  session_start();
  // if (isset($_SESSION["user_id"]) && is_numeric($_SESSION["user_id"])) {
  //     header("location:/members/index.php");
  // }
  $dbh = Db::getInstance();
  try {
    $dbh->beginTransaction();
    $stmt = $dbh -> prepare ("select u.id, u.url, u.title, c.user_id, c.comment from urls as u join comments as c on u.id = c.url_id order by c.updated_at DESC");
    $stmt->execute();
    $dbh->commit();
  } catch (Exception $e) {
    $dbh->rollBack();
    echo "例外キャッチ：", $e->getMessage(), "\n";
  }
  $results = $stmt->fetchAll();
  foreach ($results as $result) {
    $contents[$result["id"]][] = $result;  
  }

?>
