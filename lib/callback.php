<?php

session_start();

require_once(dirname(__FILE__) . '/./config.php');
require_once(dirname(__FILE__) . '/./db.php');
require_once(dirname(__FILE__) . '/./twitteroauth/autoload.php');

use Abraham\TwitterOAuth\TwitterOAuth;

//login.phpでセットしたセッション
$request_token = [];  // [] は array() の短縮記法。詳しくは以下の「追々記」参照
$request_token['oauth_token'] = $_SESSION['oauth_token'];
$request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];

//Twitterから返されたOAuthトークンと、あらかじめlogin.phpで入れておいたセッション上のものと一致するかをチェック
if (isset($_REQUEST['oauth_token']) && $request_token['oauth_token'] !== $_REQUEST['oauth_token']) {
    die( 'Error!' );
}

//OAuth トークンも用いて TwitterOAuth をインスタンス化
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $request_token['oauth_token'], $request_token['oauth_token_secret']);

//アプリでは、access_token(配列になっています)をうまく使って、Twitter上のアカウントを操作していきます
$_SESSION['access_token'] = $connection->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));
/*
ちなみに、この変数の中に、OAuthトークンとトークンシークレットが配列となって入っています。
*/

//セッションIDをリジェネレート
session_regenerate_id();

$dbh = Db::getInstance();
// dbへの登録
if (isset($_SESSION['access_token']['user_id']))
{
  // twitter user_idが登録されているか確認
  $sql = "select * from twitter_users where user_id = :user_id;";
  try {
    $dbh->beginTransaction();
    $stmt = $dbh -> prepare ($sql);
    $stmt->bindParam(':user_id', $_SESSION['access_token']['user_id'] , PDO::PARAM_STR);
    $stmt->execute();
    $dbh->commit();
  } catch (Exception $e) {
    $dbh->rollBack();
    echo "例外キャッチ：", $e->getMessage(), "\n";
  }
  $results = $stmt->fetchAll();

  // 結果が空だったら登録
  if (empty($results)) {
    $sql = "insert into twitter_users (user_id, name, screen_name, created_at) values (:user_id, :name, :screen_name, null);";
    try {
      $dbh->beginTransaction();
      $stmt = $dbh -> prepare ($sql);
      $stmt->bindParam(':user_id', $_SESSION['access_token']['user_id'] , PDO::PARAM_STR);
      $stmt->bindParam(':name', $_SESSION['access_token']['screen_name'] , PDO::PARAM_STR);
      $stmt->bindParam(':screen_name', $_SESSION['access_token']['screen_name'] , PDO::PARAM_STR);
      $stmt->execute();
      $member_id = $dbh->lastinsertId('id');
      $dbh->commit();
    } catch (Exception $e) {
      $dbh->rollBack();
      echo "例外キャッチ：", $e->getMessage(), "\n";
    }
  } else {
    //違ったら、user_id基準でデータを取得
    $sql = "select * from twitter_users where user_id = :user_id;";
    try {
      $dbh->beginTransaction();
      $stmt = $dbh -> prepare ($sql);
      $stmt->bindParam(':user_id', $_SESSION['access_token']['user_id'] , PDO::PARAM_STR);
      $stmt->execute();
      $dbh->commit();
    } catch (Exception $e) {
      $dbh->rollBack();
      echo "例外キャッチ：", $e->getMessage(), "\n";
    }
    $memberResults = $stmt->fetchAll();
    $member_id = $memberResults[0]['id'];
  }
} else {
  // sessionがうまくセットされなかった場合は、404へ
  header("HTTP/1.1 404 Not Found");
  include (dirname(__FILE__) . '/../../404.php');
  exit;
}

$url = "/members/?id=" . $member_id;
//マイページへリダイレクト
header( "location: $url" );