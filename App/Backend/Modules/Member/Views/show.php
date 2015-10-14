<p style="text-align: center">Il y a actuellement <?= $nombreMembre ?> membres. En voici la liste :</p>

<table>
    <tr><th>Login</th><th>Date d'inscription</th><th>Email</th><th>Action</th></tr> <!-- Voire profil delete profil-->
    <?php
    date_default_timezone_set('Europe/Paris');
    foreach ($listeMembre as $membre)
    {

        if($user->getAttribute('groupe_user')==1 || ($user->getAttribute('groupe_user')==2 && $user->getAttribute('id_user')== $membre->id()))
        {
            echo '<tr><td>', $membre->login(), '</td><td>', $membre->dateRegistration()->format('d/m/Y à H\hi'), '</td><td>', $membre->email(), '</td><td><a href="profil-', $membre->id(), '.html"><img src="/images/update.png" alt="Voir le profil" /></a> <a href="profil-delete-', $membre->id(), '.html"><img src="/images/delete.png" alt="Supprimer le profil" /></a></td></tr>', "\n";
        }
        else
        {
            echo '<tr><td>', $membre->login(), '</td><td>', $membre->dateRegistration()->format('d/m/Y à H\hi'), '</td><td>', $membre->email(), '</td><td><a href="profil-', $membre->id(), '.html"><img src="/images/update.png" alt="Voir le profil" /></a></td></tr>', "\n";
        }

    }
    ?>
</table>