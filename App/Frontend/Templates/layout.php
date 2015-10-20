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
        <p><?php echo $header ?></p>
      </header>
      <nav>
        <ul>
          <?php foreach($Menu as $redirection) {?>
          <li><a href="<?=$redirection['link']?>"><?=$redirection['text']?></a></li>
          <?php }?>
        </ul>
      </nav>
      <!--
      <nav>
        <ul>
          <li><a href="/">Accueil</a></li>

          <li><a href="/admin/">Afficher les news</a></li>
          <li><a href="/admin/profil-list.html">Membres</a></li>
          <li><a href="/admin/news-insert.html">Ajouter une news</a></li>
          <li><a href="/admin/profil-.html">Afficher mon profil</a></li>
          <li><a href="/admin/deconnexion.html">Déconnexion</a></li>

          <li><a href="/inscription.html">Inscription</a></li>
          <li><a href="/connexion.html">Connexion</a></li>


        </ul>
      </nav>-->
 
      <div id="content-wrap">
        <section id="main">
          <?php if ($user->hasFlash()) echo '<p style="text-align: center;">', $user->getFlash(), '</p>'; ?>
 
          <?= $content ?>
        </section>
      </div>
 
      <footer></footer>
    </div>
  </body>
</html>