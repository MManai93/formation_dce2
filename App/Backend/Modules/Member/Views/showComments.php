<p style="text-align: center">Il y a actuellement <?= $nombreComment ?> commentaires postés par le membre. En voici la liste :</p>

<table>
    <tr><th>Contenu</th><th>Date</th><th>News</th></tr>
    <?php
    date_default_timezone_set('Europe/Paris');
    foreach ($listeComment as $comment)
    {
        echo '<tr><td>', $comment->content(), '</td><td>',$comment->dateAdd()->format('d/m/Y à H\hi'),'</td><td><a href="../news-', $comment->news_id(), '.html"><img src="/images/update.png" alt="Voir la news" /></a></td></tr>', "\n";
    }
    ?>
</table>

<a href="/admin/profil-<?=$idURL?>.html">Retour</a>