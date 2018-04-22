<?
  session_start();
  require_once(dirname(__FILE__) . '/./config.php');
  require_once(dirname(__FILE__) . '/../vendor/autoload.php');
  require_once(dirname(__FILE__) . '/./db.php');

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

  $_SESSION['user_id'] = $_SESSION['access_token']['user_id'];
  $_SESSION['user_name'] = $_SESSION['access_token']['screen_name'];

  //セッションIDをリジェネレート
  session_regenerate_id();

  $dbh = Db::getInstance();
  try {
    $dbh->beginTransaction();
    $stmt = $dbh -> prepare ("insert into twitter_users (id, name, created_at) values (:id, :name, null);");
    $stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_STR);
    $stmt->bindParam(':name', $_SESSION['user_name'], PDO::PARAM_STR);
    $stmt->execute();
    $dbh->commit();
  } catch (Exception $e) {
    $dbh->rollBack();
    echo "例外キャッチ：", $e->getMessage(), "\n";
  }

  try {
    $dbh->beginTransaction();
    $stmt = $dbh -> prepare ("insert into user_screen_name (user_id, screen_name, created_at) values (:user_id, :screen_name, null);");
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_STR);
    $stmt->bindParam(':screen_name', $_SESSION['user_name'], PDO::PARAM_STR);
    $stmt->execute();
    $dbh->commit();
  } catch (Exception $e) {
    $dbh->rollBack();
    echo "例外キャッチ：", $e->getMessage(), "\n";
  }

  try {
    $dbh->beginTransaction();
    $stmt = $dbh -> prepare ("select * from user_screen_name where user_id = :user_id order by created_at;");
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll();
    $dbh->commit();
  } catch (Exception $e) {
    $dbh->rollBack();
    echo "例外キャッチ：", $e->getMessage(), "\n";
  }

  try {
    $dbh->beginTransaction();
    $stmt = $dbh -> prepare ("update twitter_users set screen_name_id = :screen_name_id where id = :id;");
    $stmt->bindParam(':screen_name_id', $results[0]['id'], PDO::PARAM_STR);
    $stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_STR);
    $stmt->execute();
    $dbh->commit();
  } catch (Exception $e) {
    $dbh->rollBack();
    echo "例外キャッチ：", $e->getMessage(), "\n";
  }

  //マイページへリダイレクト
  header('location: /members/name.php');
?>