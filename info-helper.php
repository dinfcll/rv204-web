<?php

function estRetourFormulaire()
{
    return isset($_POST['couleurpreferee']);
}

function utilisateurConnecte()
{
    return isset($_SESSION['nom_utilisateur']);
}

function retourneImageHex()
{
    $uploaddir = dirname(__FILE__) . '/tmp_uploads/';

    if (!file_exists($uploaddir)) {
        mkdir($uploaddir, 0777, true);
    }

    $uploadfile = $uploaddir . basename($_FILES['image']['name']);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
        $output = exec("hexdump -ve '1/1 \"%0.2X\"' " . $uploadfile . " 2>&1", $erreur);

        if (sizeof($erreur) > 1) {
            return "";
        }
    } else {
        return "";
    }

    return $output;
}

function maj($utilisateur, $monacces)
{
    $couleur = $_POST['couleurpreferee'];
    $password = $utilisateur['password'];

    if ($_POST['password1'] != "") {
        if ($_POST['password1'] == $_POST['password2']) {
            $password = $_POST['password1'];
        } else {
            return messageErreur("Les mots de passe sont différents");
        }
    }

    $image = "";

    if (isset($_FILES['image'])) {
        if ($_FILES['image']['size'] > 0) {
            $image = retourneImageHex();

            if ($image == "") {
                return messageErreur("Erreur dans l'image");
            }
        }
    }

    $monacces->majEmploye($utilisateur['id'], $password, $couleur, $image);

    return messageSucces("Votre compte a bien été mis à jour");
}

function messageErreur($message) {
    return "<div class=\"alert alert-danger\">".$message."</div>";
}

function messageSucces($message) {
    return "<div class=\"alert alert-success\">".$message."</div>";
}
