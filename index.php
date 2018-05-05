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
    <title>puprl（パップル） | webでブックマークするサービス</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.0.3/milligram.min.css">
    <link rel="stylesheet" href="/css/index.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="/js/puprl.js"></script>

  </head>

  <body>
    <div class="root">
      <header class="header">
        <div class="row lead">
          <p class="column">puprl（パップル） | webでブックマークするサービス</p>
        </div>
        <div class="row header_inner">
          <div class="column column-30 t-center">
            <h1><a href="/"><img src="/images/logo.svg" alt="puprlロゴ"></a></h1>
          </div>
          <div class="column column-60">
            <form action="/search/" method="GET" class="row">
              <p class="column column-80"><input class="text" type="text" name="q" required value="" placeholder=""></p>
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
          <? foreach(@$contents["urls"] ?: array() as $url): ?>
          <div class="bookmark">
            <div class="titles row">
                <div class="title column column-75">
                  <h2><a href="<?= h($url['url']) ?>" target="_blank"><?= h($url['title']) ?></a></h2>
                </div>
                <div class="column column-20">
                  <? if ($url['type'] === "image"): ?>
                  <img src="<?= $url['url'] ?>">
                  <? else: ?>
                  <img class="opg" opghtml="<?= $url['url'] ?>" src="/images/loading.gif">
                  <? endif; ?>
                </div>
            </div>
            <div class="row">
            </div>
            <div class="comments">
              <? $i = 0; ?>
              <? foreach(@$contents['comments'][$url['id']] ?: array() as $val):?>
              <? if($i < 3): ?>
              <p><a class="user" href="/members/?id=<?= h($val['member_id']) ?>"><?= h($val['screen_name']) ?></a><?= h($val['comment']) ?><span class="time">(<?= h($val['created_at']) ?>)</span></p>
              <? $i++; ?>
              <? endif; ?>
              <? endforeach; ?>
              <p class="other_comments"><a href="/url/?id=<?= h($url['id']) ?>">他のコメントを見る</a></p>
            </div>
          </div>
          <? endforeach; ?>
        </div>
        <?
          $page = @$_GET['page'];
          if (!isset($_GET['page']) || !is_numeric($_GET["page"]) || @$_GET['page'] == false) {
            $page = 0;
          } 
        ?>
        <p class="next_page">
        <? if (@$page != 0): ?>
        <a class="next_page_button button" href="/?page=<?= $page - 1 ?>">前のページ</a>
        <? endif; ?>
        <? if (!empty($contents['urls'])): ?>
        <a class="next_page_button button" href="/?page=<?= $page + 1 ?>">次のページ</a>
        <? endif; ?></p>
      </div>
      <footer class="row in-center">
<? require_once(dirname(__FILE__) . '/./lib/common/footer.php'); ?>
      </footer>
    </div>
  </body>
</html>
