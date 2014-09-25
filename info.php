<?php
include "accesbd.php";
include "info-helper.php";

session_start();

$utilisateur = "";
$message = "";
$monacces = new AccesBD();

if(utilisateurConnecte()) {
    $utilisateur = $monacces->recupererUtilisateur($_SESSION['nom_utilisateur']);

    if(estRetourFormulaire()) {
        $message = maj($utilisateur, $monacces);

        $utilisateur = $monacces->recupererUtilisateur($_SESSION['nom_utilisateur']);
    }

} else {
    header('Location: index.php');
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">

    <title>RV204 - web</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">

    <link href="css/style.css" rel="stylesheet">
</head>

<body>
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php">RV204</a>
        </div>
        <?php
            if($utilisateur['isAdmin'] != 0) {
                echo '<div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li><a href="admin.php">Admin</a></li>
                        </ul>
                      </div>';
            }
        ?>
    </div>
</div>

<div class="container">
    <div class="page-header">
        <?php echo $message ?>
        <h1><?php echo $utilisateur['nom_complet'] ?> (<?php echo $utilisateur['username'] ?>)</h1>
    </div>

    <form method="post" action="info.php" enctype="multipart/form-data">
        <h3>Votre couleur</h3>
        <input type="color" name="couleurpreferee" value="<?php echo $utilisateur['couleur'] ?>">

        <h3>Votre image</h3>
        <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
        <input type="file" name="image" accept="image/*" id="image"><br>
        <div id="image-container">
            <?php
                if($utilisateur['image'] != "") {
                    echo '<img src="image.php?id='.$utilisateur['id'].'" width="300px"><br>';
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

<!-- Bootstrap et jQuery -->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script language="JavaScript">
    function afficheImage(evt) {
        var file = evt.target.files[0];

        if(!file.type.match('image.*')) {
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
</body>
</html>
