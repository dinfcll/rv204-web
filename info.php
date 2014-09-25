<?php
include "accesbd.php";
include "info-helper.php";

session_start();

$utilisateur = "";
$message = "";
$monacces = new AccesBD();

if (utilisateurConnecte()) {
    $utilisateur = $monacces->recupererUtilisateur($_SESSION['nom_utilisateur']);

    if (estRetourFormulaire()) {
        $message = maj($utilisateur, $monacces);

        $utilisateur = $monacces->recupererUtilisateur($_SESSION['nom_utilisateur']);
    }

} else {
    header('Location: index.php');
}

include "header.php";

?>

<div class="container">
    <div class="page-header">
        <?php echo $message ?>
        <h1><?php echo $utilisateur['nom_complet'] ?> (<?php echo $utilisateur['username'] ?>)</h1>
    </div>

    <form method="post" action="info.php" enctype="multipart/form-data">
        <h3>Votre couleur</h3>
        <input type="color" name="couleurpreferee" value="<?php echo $utilisateur['couleur'] ?>">

        <h3>Votre image</h3>
        <input type="hidden" name="MAX_FILE_SIZE" value="5000000"/>
        <input type="file" name="image" accept="image/*" id="image"><br>

        <div id="image-container">
            <?php
            if ($utilisateur['image'] != "") {
                echo '<img src="image.php?id=' . $utilisateur['id'] . '" width="300px"><br>';
            }
            ?>
        </div>

        <h3>Votre mot de passe</h3>
        <label>
            Mot de passe : <input type="password" name="password1">
        </label><br>
        <label>
            Entrez de nouveau : <input type="password" name="password2">
        </label><br>

        <button type="submit" class="btn btn-primary">Modifier</button>
    </form>

</div>

<script language="JavaScript">
    function afficheImage(evt) {
        var file = evt.target.files[0];

        if (!file.type.match('image.*')) {
            return;
        }

        var reader = new FileReader();

        reader.onload = (function() {
            return function(e) {
                document.getElementById('image-container').innerHTML =
                    '<img src="' + e.target.result + '" width=300px><br>';
            };
        })();

        reader.readAsDataURL(file);
    }

    document.getElementById('image').addEventListener('change', afficheImage, false);
</script>

<? include "footer.php"; ?>
