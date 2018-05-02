<?php
require_once(dirname(__FILE__) . '/./lib/index.php');
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
    <title>puprl | webでブックマークするサービス</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.0.3/milligram.min.css">
    <link rel="stylesheet" href="/css/index.css">
  </head>

  <body>
    <div class="root">
      <header class="header">
        <div class="row header_inner">
          <div class="column column-30 t-center">
            <h1><a href="/"><img src="/images/logo.svg" alt="puprlロゴ"></a></h1>
          </div>
          <div class="column column-60">
            <form action="/search/" method="GET" class="row">
              <p class="column column-80"><input class="text" type="text" name="q" value="" placeholder=""></p>
              <p class="column column-10"><input class="submit" type="submit" value="search"></p>
              <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
            </form>
            <? if (isset($_SESSION['user_id'])): ?>
            <div class="float-right">
              <a class="to_mypage" href="/members/?id=<?= $_SESSION['user_id'] ?>">mypage</a>
              <a class="to_logout" href="/logout.php">logout</a>
            </div>
            <? endif; ?>
          </div>
        </div>
      </header>
      <div class="main container contents">
        <? if (!isset($_SESSION['user_id'])): ?>
        <div>
          <p  class="twitter_login t-center"><a class="button button-small" href="/twitterlogin.php">twitter login</a></p>
        </div>
        <? endif; ?>
        <div class="main container">
          <h2>このサイトについて</h2>
          <div>
            <p>puprl（パップル） - webの棚 - はソーシャルブックマークサービスです。<br>
            インターネット上で見つけたurlを保存できます。<br>
            urlにはコメントがつけられます。</p>
            <hr>
            <p>リンクは全ての人に公開されます。<br>
            リンクを登録するには、twitterでログインしてください。<br>
            （ユーザー名の変更もできるので、twitterのフォロワーに知られたくない方はユーザー名を変更してください。）</p>
          </div>
        </div>
      </div>
      <footer class="row in-center">
<? require_once(dirname(__FILE__) . '/./lib/common/footer.php'); ?>
      </footer>
    </div>
  </body>
</html>
