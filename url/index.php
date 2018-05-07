<?php
require_once(dirname(__FILE__) . '/.././lib/url/index.php');
?>
<!doctype html>
<html lang="ja">
  <head>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-118752986-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-118752986-1');
</script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, minimum-scale=0.5,user-scalable=yes,initial-scale=1.0" />
    <meta name="description" content="puprlはwebでブックマークするサービスです。">
    <meta name="author" content="">
    <link rel="icon" href="/images/favicon.ico">

<?
foreach(@$contents ?: array() as $title){
  $titles = $title;
}
?>
<!-- HTML Meta Tags -->
<title>puprl（パップル） <?= h($title[0]['title']) ?>へのコメント | webでブックマークするサービス</title>
<meta name="description" content="puprlはwebでブックマークするサービスです。">

<!-- Google / Search Engine Tags -->
<meta itemprop="name" content="puprl（パップル） <?= h($title[0]['title']) ?>へのコメント | webでブックマークするサービス">
<meta itemprop="description" content="puprlはwebでブックマークするサービスです。">
<meta itemprop="image" content="https://puprl.com/images/puprl_opg.jpg">

<!-- Facebook Meta Tags -->
<meta property="og:url" content="https://puprl.com">
<meta property="og:type" content="website">
<meta property="og:title" content="puprl（パップル） <?= h($title[0]['title']) ?>へのコメント | webでブックマークするサービス">
<meta property="og:description" content="puprlはwebでブックマークするサービスです。">
<meta property="og:image" content="https://puprl.com/images/puprl_opg.jpg">

<!-- Twitter Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="puprl（パップル） <?= h($title[0]['title']) ?>へのコメント | webでブックマークするサービス">
<meta name="twitter:description" content="puprlはwebでブックマークするサービスです。">
<meta name="twitter:image" content="https://puprl.com/images/puprl_opg.jpg">

<!-- Meta Tags Generated via http://heymeta.com -->

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.0.3/milligram.min.css">
    <link rel="stylesheet" href="/css/index.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="/js/puprl.js"></script>
  </head>

  <body class="other">
    <div class="">
      <header class="header">
        <div class="row lead">
          <p class="column">puprl（パップル） | webでブックマークするサービス</p>
        </div>
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
                  <? if ($content[0]['url'] === "image"): ?>
                  <img src="<?= $content[0]['url'] ?>">
                  <? else: ?>
                  <img class="opg" opghtml="<?= $content[0]['url'] ?>" src="/images/loading.gif">
                  <? endif; ?>
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
          <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-show-count="false">Tweet</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
        </div>
      </div>
      <footer class="row in-center">
<? require_once(dirname(__FILE__) . '/.././lib/common/footer.php'); ?>
      </footer>
    </div>
  </body>
</html>
