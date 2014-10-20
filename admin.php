<?php

include "admin-verification.php";
include "header.php";
include_once "constants.php";

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

        <em>N.B. Tous les Raspberry Pi utilisent le port <b><?php echo RPI_PORT ?></b> pour communiquer.</em>

        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Employé</th>
                <th>Utilisateur</th>
                <th>Image</th>
                <th>Admin</th>
                <th>Modifier</th>
                <th>Supprimer</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $users = (new EmployeDao())->getAll();
            foreach ($users as $user) {
                echo "<tr>";
                echo "<td>" . $user['id'] . "</td>";
                echo "<td>" . $user['firstName'] . " " . $user['lastName'] . "<br><a href=\"mailto:" . $user['email']
                    . "\">" . $user['email'] . "</a>";

                if($user['rpiIpLastInteger'] > 0) {
                    echo "<br><b>Raspberry Pi : " . RPI_IP_BEGINNING_ADRESS . $user['rpiIpLastInteger'] . "</b>";
                }

                echo "</td>";
                echo "<td>" . $user['username'] . "</td>";
                if ($user['image'] != "") {
                    echo "<td><a href='image.php?id=" . $user['id'] . "'><img src='image.php?id=" . $user['id']
                        . "' width=100px></a></td>";
                } else {
                    echo "<td></td>";
                }
                if ($user['isAdmin'] != 0) {
                    echo "<td>Oui</td>";
                } else {
                    echo "<td>Non</td>";
                }

                echo "<td><a href='admin-put.php?id=" . $user['id'] . "'>Modifier</a></td>";

                echo "<td><a href='#' onclick=\"sweetConfirmDelete(" . $user['id'] . ");\">Supprimer</a></td>";

                echo "</tr>";
            }
            ?>
            </tbody>
        </table>

        <a href="admin-put.php">Nouvel utilisateur</a>

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
