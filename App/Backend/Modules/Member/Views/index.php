<h2>Informations personnelles de <?=$login?></h2>

<label>E-mail :</label> <?=$email ?>
<label>Date d'inscription :</label> <?=$dateInscription ?>
<label>Date de naissance :</label> <?= !empty($dateNaissance) ? $dateNaissance : 'Non renseignee' ?>
<label>Adresse :</label> <?= !empty($adresse) ? $adresse : 'Non renseignee' ?>
<label>Ville :</label> <?= !empty($ville) ? $ville : 'Non renseignee' ?>
<label>Pays :</label> <?= !empty($pays) ? $pays : 'Non renseigne' ?>
<ul>
    <li><a href="/admin/profil-news-<?=$idMembre?>.html">Afficher les news du membre</a></li>
    <li><a href="/admin/profil-comments-<?=$idMembre?>.html">Afficher les commentaires du membre</a></li>
    <?php if($user->getAttribute('groupe_user') < $groupeMembre || $user->getAttribute('id_user')==$idMembre) {?>
        <li><a href="/admin/profil-update-<?=$idMembre?>.html">Modifier les informations personnelles du membre</a></li>
        <li><a href="/admin/profil-update-login-<?=$idMembre?>.html">Modifier le login du membre</a></li>
        <li><a href="/admin/profil-update-password-<?=$idMembre?>.html">Modifier le mot de passe du membre</a></li>
        <li><a href="/admin/profil-delete-<?=$idMembre?>.html">Supprimer le membre</a></li>
    <?php }?>
</ul>
