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
        <p>members index</p>
        <p><?= $_SESSION['user_name']; ?></p>

        <div>
          <form action="/url/post.php" method="post">
            <? if (@$error["url"] == "url_error" ): ?>
            <p></p<?= @$error["url"] ?>>
            <? endif; ?>
            <div><label>url:<input type="text" name="url" value=""></label></div>
            <div><label>comment:<input type="text" name="comment" value=""></label></div>
            <input type="submit" value="登録">
            <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
          </form>
        </div>
        <div>
          <? foreach(@$contents ?: array() as $content): ?>
            <div>
              [id]<span><a href="/url/?id=<?= $content[0]['id']; ?>"><?= $content[0]['id']; ?></a></span>
              [user_id]<span><?= $content[0]['user_id']; ?></span>
              [url]<span><?= $content[0]['url']; ?></span><br>
              [title]<span><?= $content[0]['title']; ?></span><br>
              <? foreach ($content as $comment): ?>
              [comment]<span><?= $comment['comment']; ?></span><br>
              <? endforeach; ?>
            </div>
          <? endforeach; ?>
        </div>
      </div>
    </div>
    <footer>
      copylight : share ulrs
    </footer>
  </body>
</html>
