<?php

include "admin-header.php" ;

$message = "";

if (estRetourFormulaire()) {
    $message = insererUtilisateur($_POST, $monacces);
}

?>

<div class="container">
    <div class="page-header">
        <?php
        if(isset($message)) {
            echo $message;
        }
        ?>
        <h1>Créer un nouvel utilisateur</h1>
    </div>

    <form method="post" action="admin-nouveau.php" enctype="multipart/form-data">
        <label>
            Prénom : <input type="text" name="prenom" id="prenom" placeholder="(ex : Olivier)" onkeyup="proposeCourriel()">
        </label><br>

        <label>
            Nom : <input type="text" name="nomfamille" id="nomfamille" placeholder="(ex : Lafleur)" onkeyup="proposeCourriel()">
        </label><br>

        <label>
            Nom d'utilisateur : <input type="text" name="username" placeholder="(ex : lafleuro)">
        </label><br>

        <label>
            Courriel : <input type="email" name="email" id="email" placeholder="(ex: admin@admin.com)">
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

<script language="JavaScript">
    function proposeCourriel() {
        var prenom = cleanUpSpecialChars(document.getElementById('prenom').value.toLowerCase());
        var nomfamille = cleanUpSpecialChars(document.getElementById('nomfamille').value.toLowerCase());

        if(prenom != "" || nomfamile != "") {
            document.getElementById('email').value = prenom + "." + nomfamille + "@cll.qc.ca";
        }
    }

    function cleanUpSpecialChars(str)
    {
        str = str.replace(/[àáâãäå]/g,"a");
        str = str.replace(/[èéêë]/g,"e");
        str = str.replace(/[î]/g,"i");
        str = str.replace(/[ô]/g,"o");
        str = str.replace(/[ ']/g,"");
        str = str.replace(/[ûü]/g,"u");
        return str.replace(/[^a-z0-9]/gi,''); // final clean up
    }
</script>

<?php include "footer.php"; ?>
