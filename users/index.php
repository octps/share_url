<?php
  require_once(dirname(__FILE__) . '/../lib/users/index.php');
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
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="/css/index.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

    <title>share_url</title>

  </head>

  <body class="member index">
    <header>
      <a href="/logout.php">logout</a>
    </header>
    <div class="container">
      <div class="wrapper">
        <p>users index</p>
        <p>user screen_name <?= htmlspecialchars($screen_name) ?></p>
            <div>
              <? foreach($contents as $content): ?>

              [title]<span><a href="<?= $conetens[0]['url']; ?>" target="_blank"><?= $content[0]['title']; ?></a></span><br>
              <b>comment</b><br>
              <? foreach($content as $val): ?>
                <span><a href="/urls/?id=<?  ?>">[<?= $val['screen_name'] ?>]</a></span> <span><?= $val['comment']; ?></span><br>
              <? endforeach; ?>
              <? endforeach; ?>
            </div>
      </div>
    </div>
    <footer>
      copylight : share ulrs
    </footer>
  </body>
</html>
