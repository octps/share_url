<?
  require_once(dirname(__FILE__) . '/lib/signin.php');
?>
<!doctype html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, minimum-scale=0.5,user-scalable=yes,initial-scale=1.0" />
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/images/favicon.ico">
    <!-- Bootstrap読み込み（スタイリングのため） -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">

    <title>share_url</title>

  </head>

  <body>
    <div class="col-xs-6 col-xs-offset-3">
      <header>
        header
      </header>
      <div class="container">
        <p>名前とemailとパスワードを登録してください。<br>
        パスワードは8文字以上の半角英数で登録してください。<br>
        名前は4文字以上、20文字以下で登録してください。<br>
        登録後、メールアドレスに確認用メールがとどきますので、24時間いないにメール本文に記載のurlにアクセスしてください。<br>
        メール本文のurlにアクセスするまで、本登録とはなりませんのでご注意ください。<br>
        <form action="/signin.php" method="post">
          <? if (isset($error['token'])): ?>
          <p class="error"><?= @$error['token'] ?></p>
          <? endif; ?>
          <? if (isset($_GET['member'])): ?>
          <p class="error"><?= @$_GET['member'] ?></p>
          <? endif; ?>


          <? if (isset($_GET['name'])): ?>
          <p class="error"><?= @$_GET['name'] ?></p>
          <? endif; ?>
          <p>name:<input type="text" name="name" value=""></p>

          <? if (isset($_GET['email'])): ?>
          <p class="error"><?= @$_GET['email'] ?></p>
          <? endif; ?>
          <p>email:<input type="text" name="email" value=""></p>
          <? if (isset($_GET['password'])): ?>
          <p class="error"><?= @$_GET['password'] ?></p>
          <? endif; ?>
          <p>password:<input type="password" name="password" value=""></p>
          <input type="submit" value="登録">
          <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
        </form>
      </div><!-- /container -->
      <footer>
        footer
      </footer>
    </div>
  </body>
</html>
