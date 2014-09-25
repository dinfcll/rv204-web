<?php
include "accesbd.php";
include "info-helper.php";

session_start();

$utilisateur = "";
$message = messageInfo("Faites attention à ce que vous faites ici!");
$monacces = new AccesBD();

if (utilisateurConnecte()) {
    $utilisateur = $monacces->recupererUtilisateur($_SESSION['nom_utilisateur']);

    if ($utilisateur['isAdmin'] == 0) {
        header('Location: index.php');
    }

    if (estRetourFormulaire()) {
        $message = maj($utilisateur, $monacces);

        $utilisateur = $monacces->recupererUtilisateur($_SESSION['nom_utilisateur']);
    }

} else {
    header('Location: index.php');
}

?>

<!DOCTYPE html>
<!-- TODO : Extraire header -->
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
            <a class="navbar-brand" href="info.php">RV204</a>
        </div>
        <div class="collapse navbar-collapse">
            <?php
            if ($utilisateur['isAdmin'] != 0) {
                echo '<ul class="nav navbar-nav">
                        <li><a href="admin.php">Admin</a></li>
                      </ul>
                      ';
            }
            ?>
            <ul class="nav navbar-nav navbar-right">
                <!-- TODO : Se déconnecter -->
                <li><a href="#">Se déconnecter</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="container">
    <div class="page-header">
        <?php echo $message ?>
        <h1>Section Administrateur</h1>

        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Nom complet</th>
                <th>Utilisateur</th>
                <th>Couleur</th>
                <th>Image</th>
                <th>Admin</th>
                <!-- TODO : Modifier -->
                <th>Modifier</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $users = $monacces->recupererTousUtilisateurs();
            foreach ($users as $user) {
                echo "<tr>";
                echo "<td>" . $user['id'] . "</td>";
                echo "<td>" . $user['nom_complet'] . "</td>";
                echo "<td>" . $user['username'] . "</td>";
                echo "<td bgcolor=" . $user['couleur'] . "></td>";
                if ($user['image'] != "") {
                    echo "<td><img src='image.php?id=" . $user['id'] . "' width=100px></td>";
                } else {
                    echo "<td></td>";
                }
                if ($user['isAdmin'] != 0) {
                    echo "<td>Oui</td>";
                } else {
                    echo "<td>Non</td>";
                }

                echo "<td><a href='#'>Modifier</a></td>";

                echo "</tr>";
            }
            ?>
            </tbody>
        </table>

        <!-- TODO : Nouvel utilisateur -->
        <a href="#">Nouvel utilisateur</a>

    </div>

</div>

<!-- Bootstrap et jQuery -->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>
