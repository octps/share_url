<?php
  require_once(dirname(__FILE__) . '/../lib/checklogin.php');
  require_once(dirname(__FILE__) . '/../lib/members/index.php');
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
        <a href="/logout.php">logout</a>
      </header>
      <div class="container">
        <p>members index</p>
        <p><? print_r($_SESSION['user_id']); ?></p>
        <p><? print_r($_SESSION['user_name']); ?></p>

        <div>
          <form action="/url/post.php" method="post">
            <? if (@$error["url"] == "url_error" ): ?>
            <p></p<?= @$error["url"] ?>>
            <? endif; ?>
            <div><label>url:<input type="text" name="url" value=""></label></div>
            <div><label>comment:<input type="text" name="comment" value=""></label></div>
            <input type="submit" value="登録">
            <input type="hidden" name="id" value="<?= $_SESSION['user_id'] ?>">
            <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
          </form>
        </div>
      </div><!-- /container -->

      <footer>
        footer
      </footer>
    </div>
  </body>
</html>
