<?php
  // ini_set("arg_separator.output","&");
  require_once(dirname(__FILE__) . '/../../lib/db.php');
  // 二重送信対策
  // if ($_SERVER["REQUEST_METHOD"] == "GET") {
  //   $token = md5(uniqid(rand(), true));
  //   $_SESSION['token'] = $token;
  // }

  $get = $_GET;
  if (!isset($get['id'])
    || !is_numeric($get['id'])
  ) {
    header("location:/404.php");
    exit;
  }

  $dbh = Db::getInstance();
  try {
    $dbh->beginTransaction();
    $stmt = $dbh -> prepare ("select u.id, u.user_id, u.url, c.comment from urls as u join comments as c on u.user_id = c.user_id and u.id = c.url_id where c.url_id = :url_id ");
    $stmt->bindParam(':url_id', $get['id'], PDO::PARAM_STR);
    $stmt->execute();
    $dbh->commit();
  } catch (Exception $e) {
    $dbh->rollBack();
    echo "例外キャッチ：", $e->getMessage(), "\n";
  }
  $results = $stmt->fetchAll();


?>
