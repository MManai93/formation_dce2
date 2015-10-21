<p>Par <em><a href="/admin/profil-<?=$news->member_id()?>.html"><?= $news->member_login() ?></a></em>, le <?= $news->dateAdd()->format('d/m/Y à H\hi') ?></p>
<h2><?= $news->title() ?></h2>
<p><?= nl2br($news->content()) ?></p>
 
<?php if ($news->dateAdd() != $news->dateModif()) { ?>
  <p style="text-align: right;"><small><em>Modifiée le <?= $news->dateModif()->format('d/m/Y à H\hi') ?></em></small></p>
<?php } ?>

<p><strong>Tags : </strong><?php
  foreach ($listTags as $tag)
  { ?>
    <a href="tag-<?=$tag[0]?>.html"><?=$tag[0]?></a>/
  <?php
  }
  ?>
</p>
<?php
if (empty($comments))
{
?>
<p class="no_comment">Aucun commentaire n'a encore été posté. Soyez le premier à en laisser un !</p>

<?php
}
else
  echo '<p class="alert_5_last_comments"><em>Liste des 5 derniers commentaires</em></p>';
foreach ($comments as $comment)
{
?>
<fieldset class="comment" comment-id="<?= $comment->id()?>" news-id="<?=$comment->news_id()?>">
  <legend>
    Posté par <strong><?php if($comment->ghost_author()) { ?>
        <a href="/profil-ghost-<?=$comment->ghost_author()?>.html"><?=htmlspecialchars($comment->ghost_author())?></a>
      <?php } else { ?><a href="/admin/profil-<?=$comment->member_id()?>.html"><?=htmlspecialchars($comment->member_login())?><?php }   ?></a>
    </strong> le <?= $comment->dateAdd()->format('d/m/Y à H\hi') ?>
    <?php if ($comment->dateAdd() != $comment->dateModif()) { ?><small><em>Modifiée le <?= $comment->dateModif()->format('d/m/Y à H\hi') ?></em></small><?php }?>
    <em><?= $comment->ghost_email() ? htmlspecialchars($comment->ghost_email()) : htmlspecialchars($comment->member_email())?></em>
    <?php if ($user->isAuthenticated()) { if($user->getAttribute('groupe_user')==1 || ($user->getAttribute('groupe_user')==2 && $user->getAttribute('id_user')==$comment->member_id())) { ?> -
      <a href="admin/comment-update-<?= $comment->id() ?>.html">Modifier</a> |
      <a href="admin/comment-delete-<?= $comment->id() ?>.html">Supprimer</a>
    <?php } }  ?>
  </legend>
  <p><?= nl2br(htmlspecialchars($comment->content())) ?></p>
</fieldset>

<?php
}
?>

<p><a href="commenter-<?= $news->id() ?>.html">Ajouter un commentaire</a></p>
<div class="data" data-news_id="<?=$news->id()?>" data-groupe_user="<?=$user->getAttribute('groupe_user')?>" data-id_user="<?=$user->getAttribute('id_user')?>"></div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
<script type="text/javascript" src="loadComments.js"></script>
<button id="show_new" onclick="loadComments(this.id)">Nouveaux commentaires</button>
<?php
  if($display_button_show_more)
  {
    echo '<button id="show_more" onclick="loadComments(this.id)">Voir plus</button>';
  }
?>