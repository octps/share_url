<?php
require_once(dirname(__FILE__) . '/.././lib/members/me.php');
// echo ("<pre>");
// print_r($contents);
// echo ("</pre>");
// print_r($user);
?>
<!doctype html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, minimum-scale=0.5,user-scalable=yes,initial-scale=1.0" />
    <meta name="description" content="puprlはwebでブックマークするサービスです。">
    <meta name="author" content="">
    <link rel="icon" href="/images/favicon.ico">

    <!-- css framework読み込み（スタイリングのため） -->
    <title>puprl <?= h($user[0]['screen_name']) ?>のユーザー名の変更ページ | webでブックマークするサービス</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.0.3/milligram.min.css">
    <link rel="stylesheet" href="/css/index.css">
  </head>

  <body class="other members">
    <div class="">
      <header class="header">
        <div class="row header_inner">
          <div class="column column-30 t-center">
            <span class="h1"><a href="/"><img src="/images/logo.svg" alt="puprlロゴ"></a></span>
          </div>
          <div class="column column-60">
            <form action="/search/" method="GET" class="row">
              <p class="column column-80"><input class="text" type="text" name="q" value="" placeholder=""></p>
              <p class="column column-10"><input class="submit" type="submit" value="search"></p>
              <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
            </form>
            <? if (isset($_SESSION['user_id'])): ?>
            <div class="float-right">
              <a class="to_mypage" href="/members/?id=<?= $_SESSION['user_id'] ?>">mypageへ</a>
              <a class="to_logout" href="/logout.php">logout</a>
            </div>
            <? endif; ?>
          </div>
        </div>
      </header>
      <div class="main container contents">
        <div class="url_form_wrapper">
          <h1 class="h2">ユーザー名の変更</h1>
        </div>
        <div class="main container">
          <form action="/members/me.php" method="post" class="url_form container">
            <fieldset>
              <label for="nameField">ユーザー名</label>
              <? if (isset($_SESSION['error'])): ?>
              <p style="color:red"><?= $_SESSION['error'] ?></p>
              <? endif; ?>
              <input type="text" name="name" placeholder="<?= h($user[0]['screen_name']) ?>" id="nameField" value="<?= h($user[0]['screen_name']) ?>">
              <input class="" type="submit" value="ユーザー名を変更する">
              <div class="float-right return_user_top">
                <a href="/members/?id=<?= $_SESSION['user_id'] ?>"><?= h($user[0]['screen_name']) ?>のページへ戻る</a>
              </div>
              <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
            </fieldset>
          </form>
        </div>
      </div>
      <footer class="row in-center">
<? require_once(dirname(__FILE__) . '/.././lib/common/footer.php'); ?>
      </footer>
    </div>
  </body>
</html>
