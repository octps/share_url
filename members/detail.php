<?php
  // require_once(dirname(__FILE__) . '/../lib/checklogin.php');
  require_once(dirname(__FILE__) . '/../lib/members/detail.php');
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
        <p><?= @$_SESSION['user_name']; ?></p>
        <div>
          <? foreach($results as $result): ?>
            <div>
              [id]<span><?= $result['id']; ?></span>
              [user_id]<span><?= $result['user_id']; ?></span>
              [url]<span><?= $result['url']; ?></span>
              [comment]<span><?= $result['comment']; ?></span>
            </div>
          <? endforeach; ?>
        </div>
      </div><!-- /container -->

      <footer>
        footer
      </footer>
    </div>
  </body>
</html>
