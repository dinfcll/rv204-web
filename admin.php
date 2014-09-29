<?php include "admin-header.php"; ?>

<div class="container">
    <div class="page-header">
        <?php
        if (isset($message)) {
            echo $message;
        }
        ?>
        <h1>Section Administrateur</h1>

        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Employé</th>
                <th>Utilisateur</th>
                <th>Couleur</th>
                <th>Image</th>
                <th>Admin</th>
                <!-- TODO : Modifier -->
                <th>Modifier</th>
                <th>Supprimer</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $users = $monacces->recupererTousUtilisateurs();
            foreach ($users as $user) {
                echo "<tr>";
                echo "<td>" . $user['id'] . "</td>";
                echo "<td>" . $user['firstName'] . " " . $user['lastName'] . "<br><a href=\"mailto:" . $user['email']
                    . "\">" . $user['email'] . "</a></td>";
                echo "<td>" . $user['username'] . "</td>";
                echo "<td bgcolor=" . $user['color'] . "></td>";
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

                echo "<td><a href='#'>Modifier</a></td>";

                echo "<td><a href='admin-supprimer.php?id=" . $user['id']
                    . "' onclick=\"return confirm('Êtes vous sûr de vouloir supprimer l\'utilisateur?')\">Supprimer</a></td>";

                echo "</tr>";
            }
            ?>
            </tbody>
        </table>

        <a href="admin-nouveau.php">Nouvel utilisateur</a>

    </div>

</div>

<?php include "footer.php"; ?>
