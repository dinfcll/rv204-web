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
        <h1><?php echo $utilisateur['firstName'] ?> (<?php echo $utilisateur['username'] ?>)</h1>
    </div>

    <form method="post" action="info.php" enctype="multipart/form-data">
        <h3>Votre couleur</h3>
        <input type="color" name="couleurpreferee" value="<?php echo $utilisateur['color'] ?>">

        <h3>Votre courriel</h3>
        <input type="email" name="email" value="<?php echo $utilisateur['email'] ?>">

        <h3>Votre image</h3>
        <input type="hidden" name="MAX_FILE_SIZE" value="5000000"/>
        <input type="file" name="image" accept="image/*" id="image"><br>
        <div id="image-explication"></div>
        <div id="image-container">
            <?php
            if ($utilisateur['image'] != "") {
                echo '<img src="image.php?id=' . $utilisateur['id'] . '" width="300px" id="target"><br>';
            } else {
                echo '<img src="" width="300px" id="target"><br>';
            }
            ?>
        </div>
        <input type="hidden" id="x" name="x" />
        <input type="hidden" id="y" name="y" />
        <input type="hidden" id="w" name="w" />
        <input type="hidden" id="h" name="h" />

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
                var image = new Image();
                image.src = e.target.result;

                image.onload = function() {
                    if(this.width < 300 || this.height < 400) {
                        jQuery(function($) {
                            $('#image').val("");
                        });
                        window.alert('Image trop petite');
                    } else {
                        jcrop_api.enable();
                        jcrop_api.setImage(this.src);
                        jcrop_api.setSelect([0, 400, 300, 0]);
                        document.getElementById('image-explication').innerHTML = '<b>Veuillez sélectionner la zone d\'intérêt</b>';
                    }
                };
            };
        })();

        reader.readAsDataURL(file);
    }

    document.getElementById('image').addEventListener('change', afficheImage, false);
</script>

<?php include "footer.php"; ?>
