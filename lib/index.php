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
    $get = $_GET;
    
    $page = @$get['page'];
    if (!isset($get['page']) || !is_numeric($get["page"]) || @$get['page'] == false) {
      $page = 0;
    }

    $limit = 2;
    $offset = $limit * $page;

    // urlを取得
    $dbh = Db::getInstance();
    $sql_1 = "select distinct u.id, u.url, u.title, u.type from urls as u join comments as c on u.id = c.url_id "; 
    $sql_2 = " order by u.updated_at DESC limit :offset, :limit;";
    $sql = $sql_1 . $sql_2;

    try {
      $dbh->beginTransaction();
      $stmt = $dbh -> prepare ($sql);
      $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
      $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);      
      $stmt->execute();
      $dbh->commit();
    } catch (Exception $e) {
      $dbh->rollBack();
      echo "例外キャッチ：", $e->getMessage(), "\n";
    }
    $url_results = $stmt->fetchAll();

    $sql_where_urls = "";
    foreach (@$url_results ?: array() as $url_result) {
      $sql_where_urls .= "OR url_id = " . $url_result["id"] . " ";
    }
    $sql_where_urls = ltrim($sql_where_urls, 'OR');
    $sql_where_urls = "(" . $sql_where_urls . ")";

    if (!empty($url_results)) {
      $sql_1 = "select c.id, t.screen_name, c.comment, c.member_id, c.url_id, c.created_at, u.id from comments as c join urls as u on c.url_id = u.id join twitter_users as t on c.member_id = t.id ";
      $sql_2 = "where ";
      $sql_3 = $sql_where_urls;
      $sql_4 = " order by c.created_at DESC;";

      $sql = $sql_1 . $sql_2 . $sql_3 . $sql_4;

      try {
        $dbh->beginTransaction();
        $stmt = $dbh -> prepare ($sql);
        $stmt->execute();
        $dbh->commit();
      } catch (Exception $e) {
        $dbh->rollBack();
        echo "例外キャッチ：", $e->getMessage(), "\n";
      }
      $commets_results = $stmt->fetchAll();
      foreach($commets_results as $commets_result) {
        $commets_result_array[$commets_result['url_id']][] = $commets_result;
      }
    }

    $contents["urls"] = $url_results;
    $contents["comments"] = @$commets_result_array;

    // try {
    //   $dbh->beginTransaction();
    //   $stmt = $dbh -> prepare ("select u.id as url_id, u.url,  u.title, c.id as comment_id,  c.member_id, c.comment, t.id as twitter_id, t.screen_name, c.created_at from urls as u join comments as c on u.id = c.url_id join twitter_users as t on c.member_id = t.id order by c.created_at DESC;");
    //   $stmt->execute();
    //   $dbh->commit();
    // } catch (Exception $e) {
    //   $dbh->rollBack();
    //   echo "例外キャッチ：", $e->getMessage(), "\n";
    // }
    // $results = $stmt->fetchAll();
    // foreach ($results as $result) {
    //   // $result['opg'] = OpenGraph::fetch('$result["url"]');
    //   $contents[$result["url_id"]][] = $result;
    // }
    return $contents;
  }

}
