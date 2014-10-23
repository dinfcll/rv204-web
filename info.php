<?php
include_once "accesbd.class.php";
include_once "info-helper.php";
include_once "config.php";

session_start();

$message = "";
$employeCourant = (new EmployeBuilder())->build();

if (utilisateurConnecte()) {
    $employeDao = new EmployeDao();

    $employeCourant = $employeDao->getByUsername($_SESSION['nom_utilisateur']);

    if (estRetourFormulaire()) {
        $message = maj($employeCourant);

        $employeCourant = $employeDao->getByUsername($_SESSION['nom_utilisateur']);
    }

} else {
    header('Location: index.php');
}

include "header.php";

?>

<div class="container">
    <div class="page-header">
        <div id="message">
            <?php echo $message ?>
        </div>
        <h1><?php echo $employeCourant->getFirstName(); ?> (<?php echo $employeCourant->getUsername(); ?>)</h1>
    </div>

    <form method="post" action="info.php" enctype="multipart/form-data">
        <h3>Votre courriel</h3>
        <input type="email" name="email" value="<?php echo $employeCourant->getEmail() ?>">

        <h3>Votre image</h3>
        <input type="hidden" name="MAX_FILE_SIZE" value="5000000"/>
        <input type="file" name="image" accept="image/*" id="image"><br>
        <div id="image-explication"></div>
        <div id="image-container">
            <?php
                if ($employeCourant->getImage() != "") {
                    echo '<img src="image.php?id=' . $employeCourant->getId() . '" width="300px" id="target"><br>';
                } else {
                    echo '<img src="" width="300px" height="400px" id="target"><br>';
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

        <?php
            if($employeCourant->getHasRpi()) {
                echo '<h3>Raspberry Pi</h3>
                        <b>(Adresse : '. RPI_IP_BEGINNING_ADRESS . $employeCourant->getId() . ":" . RPI_PORT .')</b><br>
                <button type="button" class="btn" onclick="connexion()">Tester la connexion</button><br><br>';

                echo '<h4>Votre couleur</h4>';
                include "colorpicker.php";
                echo '<br><br>';
            }
        ?>
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
                        swal({
                            title: "Erreur",
                            text: 'Image trop petite. Elle doit être au moins de 300x400 px',
                            type: "error"
                        });
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

<script language="JavaScript">
    function connexion() {
        var xmlhttp = new XMLHttpRequest();
        var couleur = document.getElementById('couleur').value;

        <?php
            if($employeCourant->getHasRpi()) {
                echo "var url = '" . RPI_IP_BEGINNING_ADRESS . $employeCourant->getId() . ":" . RPI_PORT . "';";
            } else {
                echo "var url = '';";
            }
        ?>

        xmlhttp.onreadystatechange= function() {
            if(xmlhttp.readyState == 4) {
                if(xmlhttp.status == 200) {
                    swal({
                        title: "Succès",
                        text: 'Votre couleur a bien été envoyée au Raspberry Pi',
                        type: "success"
                    });
                } else {
                    swal({
                        title: "Erreur",
                        text: 'Impossible de se connecter',
                        type: "error"
                    });
                }
            }
        };

        xmlhttp.open("POST", "socket-ressource.php", true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlhttp.send("color=" + couleur + "&url=" + url);
    }
</script>

<?php include "footer.php"; ?>
