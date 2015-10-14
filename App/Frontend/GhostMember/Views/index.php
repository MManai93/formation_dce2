<p style="text-align: center">Le membre fantome <?= $ghost_author ?> a posté <?= $countComment ?> commentaires .En voici la liste :</p>
<table>
    <tr><th>Contenu</th><th>Date d'ajout</th><th>News</th></tr>
    <?php
    foreach ($listComment as $comment)
    {
        $News=$managerNews->getUnique($comment->news_id());
        echo '<tr><td>', $comment->content(), '</td><td>le ', $comment->dateAdd()->format('d/m/Y à H\hi'), '</td><td><a href="news-',$comment->news_id(),'.html">',$News->title(),'</a></td></tr>', "\n";
    }
    ?>
</table>