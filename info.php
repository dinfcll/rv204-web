<?php
include "accesbd.php";

session_start();

$utilisateur = "";
$monacces = new AccesBD();

if(isset($_SESSION['nom_utilisateur'])) {
    $utilisateur = $monacces->recupererUtilisateur($_SESSION['nom_utilisateur']);
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
    </div>
</div>

<div class="container">
    <div class="page-header">
        <h1><?php echo $utilisateur['nom_complet'] ?> (<?php echo $utilisateur['username'] ?>)</h1>
    </div>

    <form>
        <h3>Votre couleur</h3>
        <input type="color" name="couleurpreferee" value="<?php echo $utilisateur['couleur'] ?>">

        <h3>Votre image</h3>
        <input type="file">
        <img src="<?php echo $utilisateur['image'] ?>" width="300px"><br>

        <h3>Votre mot de passe</h3>
        <label>
        Mot de passe : <input type="text">
        </label><br>
        <label>
        Entrez de nouveau : <input type="text">
        </label><br>


        <button type="submit" class="btn btn-primary">Modifier</button>
    </form>

</div>

<!-- Bootstrap et jQuery -->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>
