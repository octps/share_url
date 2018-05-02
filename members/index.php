<?php
require_once(dirname(__FILE__) . '/.././lib/members/index.php');
require_once(dirname(__FILE__) . '/.././lib/OpenGraph.php');
// echo ("<pre>");
// print_r($contents);
// echo ("</pre>");

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
    <title>puprl <?= h($screen_name) ?>のページ | webでブックマークするサービス</title>
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
        <? if (!isset($_SESSION['user_id'])): ?>
        <div>
          <p  class="twitter_login t-center"><a class="button button-small" href="/twitterlogin.php">twitter login</a></p>
        </div>
        <? endif; ?>
        <div class="url_form_wrapper">
          <h1 class="h2"><?= h($screen_name) ?></a><span>のページ</span><?= (isset($_SESSION['user_id']) && @$_SESSION['user_id'] != $_GET['id']) ?  "<span>followする</span>" : '' ?></h1>
          <? if (@$_GET['id'] == @$_SESSION['user_id']): ?>
          <form action="/members/" method="post" class="url_form container">
            <fieldset>
              <label for="urlField">url</label>
              <? if (isset($_SESSION['error']['url'])): ?>
              <p style="color:red"><?= $_SESSION['error']['url'] ?></p>
              <? endif; ?>
              <input type="text" name="url" placeholder="http://pupel.com" id="urlField" value="<?= isset($_SESSION['post']['url']) ? $_SESSION['post']['url'] : "" ?>" >
              <label for="commentField">Comment</label>
              <input type="text" name="comment" placeholder="" id="commentField" value="<?= isset($_SESSION['post']['comment']) ? $_SESSION['post']['comment'] : "" ?>" ></textarea>
              <input class="" type="submit" value="登録する">
              <div class="float-right change_name">
                <a href="/members/me.php">change name</a>
              <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
              </div>
            </fieldset>
          </form>
          <div class="follower container">
            <a href="/members/follow.php" class="button">follower</a>
          </div>
          <? endif; ?>
        </div>
        <div class="main container">
          <? foreach(@$contents ?: array() as $content): ?>
          <div class="bookmark">
            <div class="titles row">
                <div class="title column column-75">
                  <h2><a href="<?= h($content[0]['url']) ?>" target="_blank"><?= h($content[0]['title']) ?></a></h2>
                  <p><a href="<?= h($content[0]['url']) ?>" target="_blank"><?= h($content[0]['url']) ?></a></p>
                </div>
                <div class="column column-20">
                  <? if ($content[0]['type'] === "image"): ?>
                  <img src="<?= $content[0]['url'] ?>">
                  <? else: ?>
                  <? $graph = OpenGraph::fetch($content[0]['url']); ?>
                  <? if (!is_null($graph->image)): ?>
                  <img src="<?= $graph->image ?>">
                  <? else: ?>
                  <img src="/images/sample.jpg">
                  <? endif; ?>
                  <? endif; ?>
                </div>
            </div>
            <div class="row">
            </div>
            <div class="comments">
              <? $i = 0; foreach(@$content ?: array() as $val):?>
              <p><a class="user" href="/members/?id=<?= h($val['member_id']) ?>"><?= h($val['screen_name']) ?></a><?= h($val['comment']) ?><span class="time">(<?= h($val['created_at']) ?>)</span></p>
              <? endforeach; ?>
              <span><a href="/url/?id=<?= h($content[0]['url_id']) ?>"><?= h($content[0]['title']) ?>へのコメントを見る</a></span>
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
