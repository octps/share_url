<?php
  // ini_set("arg_separator.output","&");
  require_once(dirname(__FILE__) . '/../../lib/db.php');
  // 二重送信対策
  if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $token = md5(uniqid(rand(), true));
    $_SESSION['token'] = $token;
  }

  $dbh = Db::getInstance();
  try {
    $dbh->beginTransaction();
    $stmt = $dbh -> prepare ("select u.id, u.url, u.title, c.user_id, c.comment from urls as u join comments as c on u.id = c.url_id where c.user_id = :user_id order by c.updated_at DESC");
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_STR);
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
  $user_id = @$results[0]["user_id"];
?>
