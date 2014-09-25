<?php
include "accesbd.php";
include "info-helper.php";

session_start();

$utilisateur = "";
$message = messageInfo("Faites attention à ce que vous faites ici!");
$monacces = new AccesBD();

if(utilisateurConnecte()) {
    $utilisateur = $monacces->recupererUtilisateur($_SESSION['nom_utilisateur']);

    if($utilisateur['isAdmin'] == 0) {
        header('Location: index.php');
    }

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
                    <th>Modifier</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $users = $monacces->recupererTousUtilisateurs();
                foreach($users as $user) {
                    echo "<tr>";
                    echo "<td>".$user['id']."</td>";
                    echo "<td>".$user['nom_complet']."</td>";
                    echo "<td>".$user['username']."</td>";
                    echo "<td bgcolor=".$user['couleur']."></td>";
                    if($user['image'] != "") {
                        echo "<td><img src='image.php?id=".$user['id']."' width=100px></td>";
                    } else {
                        echo "<td></td>";
                    }
                    if($user['isAdmin'] != 0) {
                        echo "<td>Oui</td>";
                    } else {
                        echo "<td>Non</td>";
                    }

                    echo "<td>Modifier</td>";

                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>


    </div>

</div>

<!-- Bootstrap et jQuery -->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>
