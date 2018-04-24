<?php
  // require_once(dirname(__FILE__) . '/../lib/checklogin.php');
  require_once(dirname(__FILE__) . '/../lib/url.php');
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
        <p>url detail</p>
        <div>
            <div>
              [title]<span><a href="<?= $results[0]['url']; ?>" target="_blank"><?= $results[0]['title']; ?></a></span><br>
              <h3>comment</h3>
              <? foreach($results as $result): ?>
                <span><a href="/users/?user=<? ?>">[<?= $result['screen_name'] ?>]</a></span> <span><?= $result['comment']; ?></span><br>
              <? endforeach; ?>
            </div>
        </div>
      </div><!-- /container -->

      <footer>
        footer
      </footer>
    </div>
  </body>
</html>
