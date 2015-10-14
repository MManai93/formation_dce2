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
        <p><?php if ($user->isAuthenticated()) {echo 'Connecté(e) en tant que ',$user->getAttribute('login_user');}?></p>
      </header>
 
      <nav>
        <ul>
          <li><a href="/">Accueil</a></li>
          <?php if ($user->isAuthenticated()) { ?>
          <li><a href="/admin/">Afficher les news</a></li>
          <li><a href="/admin/profil-list.html">Membres</a></li>
          <li><a href="/admin/news-insert.html">Ajouter une news</a></li>
          <li><a href="/admin/profil-<?= $user->getAttribute('id_user')?>.html">Afficher mon profil</a></li>
          <li><a href="/admin/deconnexion.html">Déconnexion</a></li>
          <?php } else {?>
          <li><a href="/inscription.html">Inscription</a></li>
          <li><a href="/connexion.html">Connexion</a></li>
          <?php }?>

        </ul>
      </nav>
 
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