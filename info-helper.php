<?php

function estRetourFormulaire()
{
    return !empty($_POST);
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

    $monacces->majEmploye($utilisateur['id'], $password, $couleur, $image, $utilisateur['isAdmin'], $utilisateur['email']);

    return messageSucces("Votre compte a bien été mis à jour");
}

function insererUtilisateur($retourFormulaire, $monacces)
{
    if($retourFormulaire['prenom'] != "" and $retourFormulaire['nomfamille'] != "") {
        $prenom = $retourFormulaire['prenom'];
        $nomfamille = $retourFormulaire['nomfamille'];
    } else {
        return messageErreur("Vous devez entrer un nom complet");
    }

    if($retourFormulaire['username'] != "") {
        $username = $retourFormulaire['username'];
    } else {
        return messageErreur("Vous devez entrer un nom d'utilisateur");
    }

    if ($retourFormulaire['password1'] == $retourFormulaire['password2']) {
        $password = $retourFormulaire['password1'];
    } else {
        return messageErreur("Les mots de passe sont différents");
    }

    if($retourFormulaire['password1'] == "") {
        return messageErreur("Vous devez entrer un mot de passe");
    }

    $couleur = $retourFormulaire['couleur'];

    if(isset($retourFormulaire['admin'])) {
        $isAdmin = 1;
    } else {
        $isAdmin = 0;
    }


    $monacces->insererEmploye($prenom, $nomfamille, $username, $password, $couleur, $retourFormulaire['email'], $isAdmin);

    return messageSucces("Le compte a bien été créé");
}

function messageErreur($message)
{
    return "<div class=\"alert alert-danger\">" . $message . "</div>";
}

function messageSucces($message)
{
    return "<div class=\"alert alert-success\">" . $message . "</div>";
}

function messageInfo($message)
{
    return "<div class=\"alert alert-info\">" . $message . "</div>";
}
