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
    <title>puprl ★ユーザー名★のマイページ | webでブックマークするサービス</title>
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
              <p class="column column-10"><input class="submit" type="submit" value="seach"></p>
            </form>
          </div>
        </div>
      </header>
      <div class="main container contents">
        <div class="url_form_wrapper">
          <h1 class="h2">★ユーザー名★の変更</h1>
        </div>
        <div class="main container">
          <form action="/members/me.php" method="post" class="url_form container">
            <fieldset>
              <label for="nameField">ユーザー名</label>
              <input type="text" name="url" placeholder="★ユーザー名★" id="nameField">
              <input class="" type="submit" value="ユーザー名を変更する">
              <div class="float-right return_user_top">
                <a href="/members/">★ユーザー名★のページへ戻る</a>
              </div>
            </fieldset>
          </form>
        </div>
      </div>
      <footer class="row in-center">
        <div class="column footer t-center">footer</div>
      </footer>
    </div>
  </body>
</html>
