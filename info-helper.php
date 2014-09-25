<?php

function estRetourFormulaire()
{
    return isset($_POST['couleurpreferee']);
}

function utilisateurConnecte()
{
    return isset($_SESSION['nom_utilisateur']);
}

function retourneImage()
{
    $uploaddir = dirname(__FILE__) . '/tmp_uploads/';

    if (!file_exists($uploaddir)) {
        mkdir($uploaddir, 0777, true);
    }

    $uploadfile = $uploaddir . basename($_FILES['image']['name']);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
        return file_get_contents($uploadfile);
    } else {
        return "";
    }
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

    $image = $utilisateur['image'];

    if ($_FILES['image']['size'] > 0) {
        $image = retourneImage();

        if ($image == "") {
            return messageErreur("Erreur dans l'image");
        }
    }

    $monacces->majEmploye($utilisateur['id'], $password, $couleur, $image, $utilisateur['isAdmin']);

    return messageSucces("Votre compte a bien été mis à jour");
}

function messageErreur($message) {
    return "<div class=\"alert alert-danger\">".$message."</div>";
}

function messageSucces($message) {
    return "<div class=\"alert alert-success\">".$message."</div>";
}

function messageInfo($message) {
    return "<div class=\"alert alert-info\">".$message."</div>";
}
