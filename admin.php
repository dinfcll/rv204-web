<?php
include "accesbd.php";
include "info-helper.php";

session_start();

$utilisateur = "";
$message = messageInfo("*** ATTENTION, VOUS ÊTES DANS LA SECTION ADMINISTRATEUR ***");
$monacces = new AccesBD();

if (utilisateurConnecte()) {
    $utilisateur = $monacces->recupererUtilisateur($_SESSION['nom_utilisateur']);

    if ($utilisateur['isAdmin'] == 0) {
        header('Location: index.php');
    }

} else {
    header('Location: index.php');
}

include "header.php";
?>

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
                    echo "<td><a href='image.php?id=" . $user['id'] . "'><img src='image.php?id=" . $user['id'] . "' width=100px></a></td>";
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

<? include "footer.php"; ?>
