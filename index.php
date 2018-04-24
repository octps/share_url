<?php
  require_once(dirname(__FILE__) . '/./lib/index.php');
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

  <body class="root">
    <h1 class="center">share urls</h1>
    <div class = "container">
      <p class="center">share urlsは、urlsをシェアするwebサービスです。</p>
      <p class="login_btn"><a class="" href="/twitter_login.php">twitter login</a></p>
      <div class="wrapper">
        <? foreach($contents as $content): ?>
          <p><a href="<?= $content[0]['url']?>" target="_blank"><?= $content[0]['title']?></a></p>
          <b>comment</b>
          <? foreach ($content as $val): ?>
          <p><?= $val['comment']?> </p>
          <? endforeach; ?>
        <? endforeach; ?>
      </div>
    </div>
    <footer>
      copylight : share ulrs
    </footer>

  </body>
</html>
