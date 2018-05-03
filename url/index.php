<?php
require_once(dirname(__FILE__) . '/.././lib/url/index.php');
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
    <title>puprl ★タイトル★のコメント | webでブックマークするサービス</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.0.3/milligram.min.css">
    <link rel="stylesheet" href="/css/index.css">
  </head>

  <body class="other">
    <div class="">
      <header class="header">
        <div class="row header_inner">
          <div class="column column-30 t-center">
            <span class="h1"><a href="/"><img src="/images/logo.svg" alt="puprlロゴ"></a></span>
          </div>
          <div class="column column-60">
            <form action="/search/" method="GET" class="row">
              <p class="column column-80"><input class="text" type="text" name="q" required value="" placeholder=""></p>
              <p class="column column-10"><input class="submit" type="submit" value="seach"></p>
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
        <? if (!isset($_SESSION['user_id'])): ?>
        <div>
          <p  class="twitter_login t-center"><a class="button button-small" href="/twitterlogin.php">twitter login</a></p>
        </div>
        <? endif; ?>
        <div class="main container">
          <? foreach(@$contents ?: array() as $content): ?>
          <div class="bookmark">
            <div class="titles row">
                <div class="title column column-75">
                  <h1 class="h2"><a href="<?= h($content[0]['url']) ?>" target="_blank"><?= h($content[0]['title']) ?></a><span>へのコメント</span></h1>
                  <p><a href="<?= h($content[0]['url']) ?>" target="_blank"><?= h($content[0]['url']) ?></a></p>
                </div>
                <div class="column column-20">
                  <img src="/images/sample.jpg">
                </div>
            </div>
            <div class="row">
            </div>
            <div class="comments">
              <? $i = 0; foreach(@$content ?: array() as $val):?>
              <p><a class="user" href="/members/?id=<?= h($val['member_id']) ?>"><?= h($val['screen_name']) ?></a><?= h($val['comment']) ?><span class="time">(<?= h($val['created_at']) ?>)</span></p>
              <? endforeach; ?>
            </div>
          </div>
          <? endforeach; ?>
        </div>
      </div>
      <footer class="row in-center">
<? require_once(dirname(__FILE__) . '/.././lib/common/footer.php'); ?>
      </footer>
    </div>
  </body>
</html>
