<?php

include_once "admin-verification.php";
include_once "info-helper.php";
include_once "accesbd.class.php";

$monacces = new AccesBD();

if(estRetourFormulaire()) {
    $adresse = trim($_POST['rpiAddress']);
    $longueur = strlen($adresse);
    if($adresse[$longueur-1] != "0") {
        $message = messageErreur("Adresse invalide");
    } else {
        $monacces->modifierRpiNetwork(substr($adresse, 0, -1));
        $message = messageSucces("Changement d'adresse IP effectué.");
    }
}

//Affichage
include "header.php";
include_once "config.php";

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
        <h1>Section Administrateur</h1>

        <form method="post" action="admin.php">
            <label>Réseau RPi :
                <input type="text" name="rpiAddress" value="<?php echo RPI_IP_BEGINNING_ADRESS . "0" ?>">
            </label>
            <button class="btn btn-primary" type="submit">Modiifer</button>
        </form><p>

        <button class="btn btn-success alignright" onclick="window.location.href='admin-put.php'">Nouvel utilisateur</button><br><br>

        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Employé</th>
                <th>Utilisateur</th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $users = (new EmployeDao())->getAll();
            foreach ($users as $user) {
                echo "<tr>";
                echo "<td>" . $user['id'] . "</td>";
                if ($user['image'] != "") {
                    echo "<td><img src='image.php?id=" . $user['id'] . "' width=100px></td>";
                } else {
                    echo "<td></td>";
                }
                echo "<td>" . $user['firstName'] . " " . $user['lastName'];

                if ($user['isAdmin'] != 0) {
                    echo " (admin)";
                }

                echo "<br><a href=\"mailto:" . $user['email']
                    . "\">" . $user['email'] . "</a>";

                if($user['hasRpi']) {
                    echo "<br><b>RPi</b> (" . RPI_IP_BEGINNING_ADRESS . $user['id'] . ")";
                }

                echo "</td>";
                echo "<td>" . $user['username'] . "</td>";

                echo "<td><button class='btn btn-warning' onclick='window.location.href=\"admin-put.php?id=" . $user['id'] . "\"'>Modifier</button></td>";

                echo "<td><button class='btn btn-danger' onclick=\"sweetConfirmDelete(" . $user['id'] . ");\">Supprimer</button></td>";

                echo "</tr>";
            }
            ?>
            </tbody>
        </table>

    </div>

</div>
<script language="JavaScript">
    function sweetConfirmDelete(id) {
        swal({
                title: "Suppression",
                text: "Êtes-vous sûr de vouloir supprimer cet usager?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Supprimer'
            },
            function(){
                window.location.href="admin-supprimer.php?id=" + id;
            });
    }
</script>
<?php include "footer.php"; ?>
