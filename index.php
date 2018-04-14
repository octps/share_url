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

    <title>share url</title>

  </head>

  <body class="root">
    <h1 class="center">share urls</h1>
    <div class = "container">
      <p class="center">share urlsは、urlsをシェアするwebサービスです。</p>
      <div class="wrapper">
<!--         <div class="form-signin">
          <h3 class="form-signin-heading">Please Log In</h3>
          <p><a class="btn btn-lg btn-primary btn-block" href="/twitter_login.php">twitter login</a></p>
        </div> -->
        <div>
          <h2>urls</h2>
          <? foreach($contents as $content): ?>
          <h3><a href="<?= $content[0]['url'] ?>" target="_blank"><?= $content[0]['url'] ?></a></h3>
          <h4>comment: </h4>
          <div class="comment">
            <? foreach($content as $value): ?>
              <span><?= $value['comment'] ?></span></br>
            <? endforeach; ?>
            <? endforeach; ?>
          </div>
        </div>
       </div>
    </div>
    <footer>
      ©share ulrs
    </footer>

  </body>
</html>
