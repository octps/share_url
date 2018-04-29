<?php
require_once(dirname(__FILE__) . '/.././lib/members/index.php');
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
            <form action="/search/" method="POST" class="row">
              <p class="column column-80"><input class="text" type="text" name="q" value="" placeholder=""></p>
              <p class="column column-10"><input class="submit" type="submit" value="seach"></p>
            </form>
          </div>
        </div>
      </header>
      <div class="main container contents">
        <div>
          <p  class="twitter_login t-center"><a class="button button-small" href="/twitterlogin.php">twitter login</a></p>
        </div>
        <div class="url_form_wrapper">
          <h1 class="h2"><?= h($screen_name) ?></a><span>のページ</span></h1>
          <form action="/members/" method="post" class="url_form container">
            <fieldset>
              <label for="urlField">url</label>
              <input type="text" name="url" placeholder="http://pupel" id="urlField">
              <label for="commentField">Comment</label>
              <input type="text" name="comment" placeholder="" id="commentField"></textarea>
              <input class="" type="submit" value="登録する">
              <div class="float-right change_name">
                <a href="/members/me.php">change name</a>
              </div>
            </fieldset>
          </form>
          <div class="follower container">
            <a href="/members/follow.php" class="button">follower</a>
          </div>
        </div>
        <div class="main container">
          <? foreach(@$contents ?: array() as $content): ?>
          <div class="bookmark">
            <div class="titles row">
                <div class="title column column-75">
                  <h2><a href="<?= h($content[0]['url']) ?>"><?= h($content[0]['title']) ?></a></h2>
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
        <div class="column footer t-center">footer</div>
      </footer>
    </div>
  </body>
</html>
