<?php
require_once(dirname(__FILE__) . '/./lib/index.php');
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
<meta name="msapplication-config" content="/images/favicons/browserconfig.xml" />
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/images/favicons/mstile-144x144.png">
<meta name="theme-color" content="#f5deb3">
<link rel="icon" type="image/x-icon" href="/images/favicons/favicon.ico">
<link rel="apple-touch-icon" sizes="180x180" href="/images/favicons/apple-touch-icon-180x180.png">
<link rel="mask-icon" href="/images/favicons/safari-icon.svg" color="#555" />
<link rel="icon" type="image/png" sizes="192x192" href="/images/favicons/android-chrome-192x192.png">
<link rel="manifest" href="/images/favicons/manifest.json">

<!-- HTML Meta Tags -->
<title>puprl（パップル）について | webでブックマークするサービス</title>
<meta name="description" content="puprlはwebでブックマークするサービスです。">

<!-- Google / Search Engine Tags -->
<meta itemprop="name" content="puprl（パップル）について | webでブックマークするサービス">
<meta itemprop="description" content="puprlはwebでブックマークするサービスです。">
<meta itemprop="image" content="https://puprl.com/images/puprl_opg.jpg">

<!-- Facebook Meta Tags -->
<meta property="og:url" content="https://puprl.com">
<meta property="og:type" content="website">
<meta property="og:title" content="puprl（パップル）について | webでブックマークするサービス">
<meta property="og:description" content="puprlはwebでブックマークするサービスです。">
<meta property="og:image" content="https://puprl.com/images/puprl_opg.jpg">

<!-- Twitter Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="puprl（パップル）について | webでブックマークするサービス">
<meta name="twitter:description" content="puprlはwebでブックマークするサービスです。">
<meta name="twitter:image" content="https://puprl.com/images/puprl_opg.jpg">

<!-- Meta Tags Generated via http://heymeta.com -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.0.3/milligram.min.css">
    <link rel="stylesheet" href="/css/index.css">
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
            <p>puprl（パップル）はwebでブックマークするサービス（ソーシャルブックマークサービス）です。</p>
            <p>インターネット上で見つけたurlを保存できます。<br>
            urlにはコメントがつけられます。</p>
            <p>フォローすると、フォローした人のコメントもmypage上で確認できます</p>
            <p>ブックマークしたurlは全ての人に公開されます。<br>
            urlを登録するには、twitterでログインしてください。<br>
            ユーザー名の変更もできるので、twitterのフォロワーに知られたくない方はユーザー名を変更してください。</p>
          </div>
        </div>
      </div>
      <footer class="row in-center">
<? require_once(dirname(__FILE__) . '/./lib/common/footer.php'); ?>
      </footer>
    </div>
  </body>
</html>
