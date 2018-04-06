<?
  require_once(dirname(__FILE__) . '/lib/confirm.php');
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
        <? if (@$_GET['confirm'] === "error"): ?>
          <p>承認エラー<br>
          <a href="/">トップページへ</a></p>
        <? else: ?>
        <p>確認用メールを送信いたしました。<br>
          メールに記載のurlにアクセスしてください。</p>
        <? endif; ?>
      </div><!-- /container -->
      <footer>
        footer
      </footer>
    </div>
  </body>
</html>
