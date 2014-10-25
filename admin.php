<?php

include_once "admin-verification.php";
include_once "info-helper.php";
include_once "accesbd.class.php";

$monacces = new AccesBD();

if (estRetourFormulaire()) {
    $message = majAdmin($_POST);
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

        <ul class="nav nav-tabs" role="tablist">
            <li class="active"><a href="#users" role="tab" data-toggle="tab">Utilisateurs</a></li>
            <li><a href="#config" role="tab" data-toggle="tab">Configuration</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="users"><br>
                <button class="btn btn-success alignleft" onclick="window.location.href='admin-put.php'">Nouvel
                    utilisateur
                </button>
                <br><br>

                <table class="table">
                    <thead>
                    <tr>
                        <th>ID</th>
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
                        echo "<td><b>" . $user['id'] . "</b></td>";
                        if ($user['picture'] != "") {
                            echo "<td><img src='picture.php?id=" . $user['id'] . "' width=100px></td>";
                        } else {
                            echo "<td></td>";
                        }
                        echo "<td>" . $user['firstName'] . " " . $user['lastName'];

                        if ($user['isAdmin'] != 0) {
                            echo " (admin)";
                        }

                        echo "<br><a href=\"mailto:" . $user['email']
                            . "\">" . $user['email'] . "</a>";

                        if ($user['hasRpi']) {
                            echo "<br><b>RPi</b> (" . retourneRpiIp($user['id']) . ")";
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
            <div class="tab-pane" id="config"><br>
                <form method="post" action="admin.php">
                    <label>Réseau RPi :
                        <input type="text" name="rpiAddress" value="<?php echo RPI_IP_BEGINNING_ADRESS ?>"
                               maxlength="20">
                    </label><br>

                    <label>Domaine Mailgun :
                        <input type="text" name="mailgunDomain" value="<?php echo $monacces->getMailgunDomain() ?>">
                    </label><br>

                    <label>Clé API Mailgun :
                        <input type="text" name="mailgunApiKey" value="<?php echo $monacces->getMailgunApiKey() ?>">
                    </label><br>
                    <button class="btn btn-primary" type="submit">Modifier</button>
                </form>
                <p></div>
        </div>


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
            function () {
                window.location.href = "admin-supprimer.php?id=" + id;
            });
    }
</script>
<?php include "footer.php"; ?>
