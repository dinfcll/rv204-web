<?php
include "accesbd.class.php";
include "info-helper.php";

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
        <h3>Votre couleur</h3>
        <input type="color" name="couleurpreferee" id="couleurpreferee" value="<?php echo $employeCourant->getColor() ?>">

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

        <h3>Raspberry Pi</h3>
        <label>
            Adresse : <input type="text" name="url" id="url" value="127.0.0.1"><br>
        </label>
        <label>
            Port : <input type="text" name="port" id="port" value="9999"><br>
        </label>
        <button type="button" class="btn" onclick="connexion()">Tester la connexion</button><br><br>

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
        var url = document.getElementById('url').value;
        var port = document.getElementById('port').value;
        var couleur = document.getElementById('couleurpreferee').value;

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
        xmlhttp.send("color=" + couleur + "&url=" + url + ":" + port);
    }
</script>

<?php include "footer.php"; ?>
