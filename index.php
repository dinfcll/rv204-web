<?php
include 'accesbd.class.php';
include 'install.php';
include 'info-helper.php';

session_start();

if(isset($_SESSION['nom_utilisateur'])) {
    header('Location: info.php');
}

$monAcces = new AccesBD();
if(isset($_POST['nom_utilisateur'])) {
    if($monAcces->utilisateurValide($_POST['nom_utilisateur'], $_POST['password']))
    {
        $_SESSION['nom_utilisateur'] = $_POST['nom_utilisateur'];
        header('Location: info.php');
    } else {
        $message = messageErreur("Utilisateur ou mot de passe invalide.");
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">

    <title>RV204 - Login</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="css/signin.css" rel="stylesheet">
</head>

<body>

<div class="container">
    <form class="form-signin" action="index.php" method="post">
        <div id="message">
            <?php
                if(isset($message)) {
                    echo $message;
                }
            ?>
        </div>
        <h2 class="form-signin-heading">RV204</h2>
        <input type="text" name="nom_utilisateur" class="form-control" placeholder="Nom d'utilisateur" required autofocus>
        <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Entrer</button>
    </form>

</div>

</body>
</html>
