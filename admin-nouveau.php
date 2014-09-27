<?php

include "admin-header.php" ;

$message = "";

if (estRetourFormulaire()) {
    $message = insererUtilisateur($_POST, $monacces);
}

?>

<div class="container">
    <div class="page-header">
        <?php echo $message ?>
        <h1>Créer un nouvel utilisateur</h1>
    </div>

    <form method="post" action="admin-nouveau.php" enctype="multipart/form-data">
        <label>
            Prénom : <input type="text" name="prenom" placeholder="(ex : Olivier)">
        </label><br>

        <label>
            Nom : <input type="text" name="nomfamille" placeholder="(ex : Lafleur)">
        </label><br>

        <label>
            Nom d'utilisateur : <input type="text" name="username" placeholder="(ex : lafleuro)">
        </label><br>

        <label>
            Couleur : <input type="color" name="couleur">
        </label><br>

        <label>
            Mot de passe : <input type="password" name="password1">
        </label><br>
        <label>
            Entrez de nouveau : <input type="password" name="password2">
        </label><br>

        <label>
            Droits d'administrateur : <input type="checkbox" name="admin">
        </label><br>

        <button type="submit" class="btn btn-primary">Créer</button>
    </form>

</div>

<?php include "footer.php"; ?>
