<?php

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
            <h1><img src="/images/logo.svg" alt="puprlロゴ"></h1>
          </div>
          <div class="column column-60">
            <form action="/search.php" method="POST" class="row">
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
        <div class="main container">
          <div class="bookmark">
            <div class="titles row">
                <div class="title column column-75">
                  <h2><a href="" target="_blank">タイトル</a></h2>
                  <p><a href="" target="_blank">http://google.com</a></p>
                </div>
                <div class="column column-20">
                  <img src="/images/sample.jpg">
                </div>
            </div>
            <div class="row">
            </div>
            <div class="comments">
              <p><a class="user" href="">ユーザー名</a>ああああああ<span class="time">(2018.04.01 23:00:00)</span></p>
              <p><a class="user" href="">ユーザー名</a>いいい、ああ、うう。<span class="time">(2018.04.01 23:00:00)</span></p>
              <p><a class="user" href="">ユーザー名</a>ああああああはしししはちはちとしは<span class="time">(2018.04.01 23:00:00)</span></p>
              <span><a href="">続きを見る</a></span>
            </div>
          </div>
        </div>
        <div class="main container">
          <div class="bookmark">
            <div class="titles row">
                <div class="title column column-75">
                  <h2><a href="" target="_blank">タイトル</a></h2>
                  <p><a href="" target="_blank">http://google.com</a></p>
                </div>
                <div class="column column-20">
                  <img src="/images/sample.jpg">
                </div>
            </div>
            <div class="row">
            </div>
            <div class="comments">
              <p><a class="user" href="">ユーザー名</a>ああああああ<span class="time">(2018.04.01 23:00:00)</span></p>
              <p><a class="user" href="">ユーザー名</a>いいい、ああ、うう。<span class="time">(2018.04.01 23:00:00)</span></p>
              <p><a class="user" href="">ユーザー名</a>ああああああはしししはちはちとしは<span class="time">(2018.04.01 23:00:00)</span></p>
              <span><a href="">続きを見る</a></span>
            </div>
          </div>
        </div>
        <div class="main container">
          <div class="bookmark">
            <div class="titles row">
                <div class="title column column-75">
                  <h2><a href="" target="_blank">タイトル</a></h2>
                  <p><a href="" target="_blank">http://google.com</a></p>
                </div>
                <div class="column column-20">
                  <img src="/images/sample.jpg">
                </div>
            </div>
            <div class="row">
            </div>
            <div class="comments">
              <p><a class="user" href="">ユーザー名</a>ああああああ<span class="time">(2018.04.01 23:00:00)</span></p>
              <p><a class="user" href="">ユーザー名</a>いいい、ああ、うう。<span class="time">(2018.04.01 23:00:00)</span></p>
              <p><a class="user" href="">ユーザー名</a>ああああああはしししはちはちとしは<span class="time">(2018.04.01 23:00:00)</span></p>
              <span><a href="">続きを見る</a></span>
            </div>
          </div>
        </div>
      </div>
      <footer class="row in-center">
        <div class="column footer t-center">footer</div>
      </footer>
    </div>
  </body>
</html>
