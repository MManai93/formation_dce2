<!DOCTYPE html>
<html>
  <head>
    <title>
      <?= isset($title) ? $title : 'Mon super site' ?>
    </title>
 
    <meta charset="utf-8" />
 
    <link rel="stylesheet" href="/css/Envision.css" type="text/css" />
  </head>
 
  <body>
    <div id="wrap">
      <header>
        <h1><a href="/">Mon super site</a></h1>
        <p><?= $header ?></p>
      </header>
      <nav>
        <ul>
          <?php foreach($Menu as $redirection) {?>
          <li><a href="<?=$redirection['link']?>"><?=$redirection['text']?></a></li>
          <?php }?>
        </ul>
      </nav>
 
      <div id="content-wrap">
        <section id="main">
          <?=$flash ?>
 
          <?= $content ?>
        </section>
      </div>
 
      <footer></footer>
    </div>
  </body>
</html>