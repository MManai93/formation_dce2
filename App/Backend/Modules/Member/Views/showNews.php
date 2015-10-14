<p style="text-align: center">Il y a actuellement <?= $nombreNews ?> news postés par l'auteur. En voici la liste :</p>

<table>
    <tr><th>Titre</th><th>Date d'ajout</th><th>Dernière modification</th><th>Action</th></tr>
    <?php
    date_default_timezone_set('Europe/Paris');
    foreach ($listeNews as $news)
    {
        echo '<tr><td>', $news['titre'], '</td><td>le ', date_format(date_create($news['dateAjout']),'d/m/Y à H\hi'), '</td><td>', ($news['dateAjout'] == $news['dateModif'] ? '-' : 'le '.date_format(date_create($news['dateModif']),'d/m/Y à H\hi')), '</td><td><a href="../news-', $news['id'], '.html"><img src="/images/update.png" alt="Voir la news" /></a></td></tr>', "\n";
    }
    ?>
</table>
<a href="/admin/profil-<?=$idURL?>.html">Retour</a>