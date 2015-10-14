<p style="text-align: center">Il y a actuellement <?= $nombreNews ?> news. En voici la liste :</p>
 
<table>
  <tr><th>Auteur</th><th>Titre</th><th>Date d'ajout</th><th>Dernière modification</th><th>Action</th></tr>
<?php
foreach ($listeNews as $news)
{
  if($user->getAttribute('groupe_user')==1 || ($user->getAttribute('groupe_user')==2 && $user->getAttribute('id_user')== $news->member_id() ))
  {
    echo '<tr><td>', $news->member_login(), '</td><td>', $news->title(), '</td><td>le ', $news->dateAdd()->format('d/m/Y à H\hi'), '</td><td>', ($news->dateAdd() == $news->dateModif() ? '-' : 'le '.$news->dateModif()->format('d/m/Y à H\hi')), '</td><td><a href="news-update-', $news->id(), '.html"><img src="/images/update.png" alt="Modifier" /></a> <a href="news-delete-', $news->id(), '.html"><img src="/images/delete.png" alt="Supprimer" /></a></td></tr>', "\n";
  }
  else
  {
    echo '<tr><td>', $news->member_login(), '</td><td>', $news->title(), '</td><td>le ', $news->dateAdd()->format('d/m/Y à H\hi'), '</td><td>', ($news->dateAdd() == $news->dateModif() ? '-' : 'le '.$news->dateModif()->format('d/m/Y à H\hi')), "\n";

  }

}
?>
</table>