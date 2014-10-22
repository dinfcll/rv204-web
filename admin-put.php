<?php

include "admin-verification.php";
include "header.php";

$message = "";
$employeCourant = (new EmployeBuilder())->build();

if(isset($_GET['id'])) {
    $employeCourant = (new EmployeDao())->getById($_GET['id']);
}

if (estRetourFormulaire()) {
    $message = putUser($employeCourant, $_POST);
    if(isset($_GET['id'])) {
        $employeCourant = (new EmployeDao())->getById($_GET['id']);
    }
}

?>

<div class="container">
    <div class="page-header">
        <div id="message">
            <?php
            if (isset($message)) {
                echo $message;
            }
            ?>
        </div>
        <?php
            if(isset($_GET['id'])) {
                echo "<h1>Modifier un utilisateur</h1>";
            } else {
                echo "<h1>Créer un nouvel utilisateur</h1>";
            }
        ?>
    </div>

    <form method="post" action="admin-put.php<?php if(isset($_GET['id'])){ echo "?id=" . $_GET['id']; } ?>" enctype="multipart/form-data">
        <?php if(isset($_GET['id'])){
            echo "<input type=\"hidden\" id=\"id\" name=\"id\" value=\"" . $_GET['id'] . "\"/>";
        } ?>

        <label>
            Prénom : <input type="text" name="prenom" id="prenom" placeholder="(ex : Olivier)"
                            onkeyup="proposeDonnees()" value="<?php echo $employeCourant->getFirstName(); ?>">
        </label><br>

        <label>
            Nom : <input type="text" name="nomfamille" id="nomfamille" placeholder="(ex : Lafleur)"
                         onkeyup="proposeDonnees()" value="<?php echo $employeCourant->getLastName(); ?>">
        </label><br>

        <label>
            Nom d'utilisateur : <input type="text" name="username" id="username" placeholder="(ex : lafleuro)"
                                       value="<?php echo $employeCourant->getUsername(); ?>">
        </label><br>

        <label>
            Courriel : <input type="email" name="email" id="email" placeholder="(ex: admin@admin.com)" value="<?php echo $employeCourant->getEmail(); ?>">
        </label><br>

        <label>
            Image : <input type="hidden" name="MAX_FILE_SIZE" value="5000000"/>
            <input type="file" name="image" accept="image/*" id="image"><br>
        </label><br>
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
            <input type="hidden" id="x" name="x"/>
            <input type="hidden" id="y" name="y"/>
            <input type="hidden" id="w" name="w"/>
            <input type="hidden" id="h" name="h"/>


        <label>
            Mot de passe : <input type="password" name="password1">
        </label><br>
        <label>
            Entrez de nouveau : <input type="password" name="password2">
        </label><br>

        <label>
            Droits d'administrateur : <input type="checkbox" id="admin" name="admin" <?php if($employeCourant->isAdmin()) {echo "checked";} ?>>
        </label><br>

        <label>
            Raspberry Pi (dernier chiffre de l'adresse IP. -1 lorsqu'il n'y en a pas) :
            <input type="text" id="rpiIpLastInteger" name="rpiIpLastInteger" value="<?php echo $employeCourant->getRpiIpLastInteger() ?>" size="3" maxlength="3">
        </label><br>

        <label>
            Couleur :</label> <br><?php include "colorpicker.php" ?>
        <br>

        <button type="submit" class="btn btn-primary">Créer</button>
    </form>

</div>

<script language="JavaScript">
    function proposeDonnees() {
        var prenom = cleanUpSpecialChars(document.getElementById('prenom').value.toLowerCase());
        var nomfamille = cleanUpSpecialChars(document.getElementById('nomfamille').value.toLowerCase());

        if (prenom != "" || nomfamile != "") {
            document.getElementById('email').value = prenom + "." + nomfamille + "@cll.qc.ca";
            document.getElementById('username').value = nomfamille + prenom[0];
        }
    }

    function cleanUpSpecialChars(str) {
        str = str.replace(/[àáâãäå]/g, "a");
        str = str.replace(/[èéêë]/g, "e");
        str = str.replace(/[îï]/g, "i");
        str = str.replace(/[ôö]/g, "o");
        str = str.replace(/[ ']/g, "");
        str = str.replace(/[ûü]/g, "u");
        return str.replace(/[^a-z0-9]/gi, ''); // final clean up
    }
</script>

<script language="JavaScript">
    function afficheImage(evt) {
        var file = evt.target.files[0];

        if (!file.type.match('image.*')) {
            return;
        }

        var reader = new FileReader();

        reader.onload = (function () {
            return function (e) {
                var image = new Image();
                image.src = e.target.result;

                image.onload = function () {
                    if (this.width < 300 || this.height < 400) {
                        jQuery(function ($) {
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

<?php include "footer.php"; ?>
