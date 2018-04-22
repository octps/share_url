<?php
  require_once(dirname(__FILE__) . '/../lib/checklogin.php');
  require_once(dirname(__FILE__) . '/../lib/members/name.php');
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
        <p>members name</p>
        <p>現在の表示名は <?= $screen_names['now_name'][0]['screen_name'] ?> です。</p>
        別の表示名を使用したい場合は以下に入力してください。<br>
        表示名は3つまで登録可能です。<br>

        <h3>表示名</h3>
        <p>登録されたurlは表示名単位で各ユーザーに表示されます。</p>
        <? if (isset($screen_names['names'][2])): ?>
        <form action="/members/name.php" method="POST">
          <? foreach($screen_names['names'] as $val): ?>
          <p><label><input type="radio" name="user_screen_name" value="<?= htmlspecialchars($val['screen_name']) ?>" <?= htmlspecialchars($val['screen_name']) == $screen_names['now_name'][0]['screen_name'] ? "checked=checked" : "" ?>> <?= htmlspecialchars($val['screen_name']) ?></label></p>
          <? endforeach ?>
          <input type="submit" value="表示名を変更する">
          <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
          <input type="hidden" name="method" value="put">
        </form>


        <? else: ?>
        <form action="/members/name.php" method="POST">
          <? if (isset($_SESSION['error']['user_screen_name'])): ?>
          <p><?= $_SESSION['error']['user_screen_name']; ?></p>
          <? endif; ?>
          <input type="text" name="user_screen_name" value=""> 
          <input type="submit" value="表示名を登録する">
          <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">  
        </form>
      <? endif; ?>

      </div><!-- /container -->

      <footer>
        footer
      </footer>
    </div>
  </body>
</html>
