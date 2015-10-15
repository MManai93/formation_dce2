<p style="text-align: center">Il y a actuellement <?= $countNews ?> news pour le tag <?=htmlspecialchars($TagName)?>. En voici la liste :</p>

<table>
    <tr><th>Titre</th><th>Auteur</th><th>Action</th></tr>
    <?php
    foreach ($listNews as $news)
    {
        echo '<tr><td>', $news->title(), '</td><td>',$news->member_login(),'</td><td><a href="../news-', $news->id(), '.html"><img src="/images/update.png" alt="Voir la news" /></a></td></tr>', "\n";
    }
    ?>
</table>