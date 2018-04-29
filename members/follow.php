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
    <title>puprl ★ユーザー名★のフォロー | webでブックマークするサービス</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.0.3/milligram.min.css">
    <link rel="stylesheet" href="/css/index.css">
  </head>

  <body class="other members">
    <div class="">
      <header class="header">
        <div class="row header_inner">
          <div class="column column-30 t-center">
            <span class="h1"><img src="/images/logo.svg" alt="puprlロゴ"></span>
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
        <div class="url_form_wrapper">
          <h1 class="h2">★ユーザー名★がフォローしているユーザー</h1>
        </div>
        <div class="main container">
          <div class="followers">
            <div>
              <a href="" class="user_name">ああああ</a>
              <form action="/members/follow.php" method="post" class="unfollow">
                <input type="hidden" name="unfollow" value="">
                <input type="submit" value="フォローをはずす">
              </form>
            </div>
            <div>
              <a href="" class="user_name">ああああ</a>
              <form action="/members/follow.php" method="post" class="unfollow">
                <input type="hidden" name="unfollow" value="">
                <input type="submit" value="フォローをはずす">
              </form>
            </div>
            <div>
              <a href="" class="user_name">ああああ</a>
              <form action="/members/follow.php" method="post" class="unfollow">
                <input type="hidden" name="unfollow" value="">
                <input type="submit" value="フォローをはずす">
              </form>
            </div>
            <div>
              <a href="" class="user_name">ああああ</a>
              <form action="/members/follow.php" method="post" class="unfollow">
                <input type="hidden" name="unfollow" value="">
                <input type="submit" value="フォローをはずす">
              </form>
            </div>
            <div>
              <a href="" class="user_name">ああああ</a>
              <form action="/members/follow.php" method="post" class="unfollow">
                <input type="hidden" name="unfollow" value="">
                <input type="submit" value="フォローをはずす">
              </form>
            </div>
            <div>
              <a href="" class="user_name">ああああ</a>
              <form action="/members/follow.php" method="post" class="unfollow">
                <input type="hidden" name="unfollow" value="">
                <input type="submit" value="フォローをはずす">
              </form>
            </div>
            <div>
              <a href="" class="user_name">ああああ</a>
              <form action="/members/follow.php" method="post" class="unfollow">
                <input type="hidden" name="unfollow" value="">
                <input type="submit" value="フォローをはずす">
              </form>
            </div>
            <div>
              <a href="" class="user_name">ああああ</a>
              <form action="/members/follow.php" method="post" class="unfollow">
                <input type="hidden" name="unfollow" value="">
                <input type="submit" value="フォローをはずす">
              </form>
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
