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
        $uploadfile = cropImage($uploadfile, $uploaddir, $_FILES['image']['type']);

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

    $monacces->majEmploye($utilisateur['id'], $password, $couleur, $image, $utilisateur['isAdmin'], $_POST['email']);

    return messageSucces("Votre compte a bien été mis à jour");
}

function insererUtilisateur($retourFormulaire, $monacces)
{
    if ($retourFormulaire['prenom'] != "" and $retourFormulaire['nomfamille'] != "") {
        $prenom = $retourFormulaire['prenom'];
        $nomfamille = $retourFormulaire['nomfamille'];
    } else {
        return messageErreur("Vous devez entrer un nom complet");
    }

    if ($retourFormulaire['username'] != "") {
        $username = $retourFormulaire['username'];
    } else {
        return messageErreur("Vous devez entrer un nom d'utilisateur");
    }

    if ($retourFormulaire['password1'] == $retourFormulaire['password2']) {
        $password = $retourFormulaire['password1'];
    } else {
        return messageErreur("Les mots de passe sont différents");
    }

    if ($retourFormulaire['password1'] == "") {
        return messageErreur("Vous devez entrer un mot de passe");
    }

    $couleur = $retourFormulaire['couleur'];

    if (isset($retourFormulaire['admin'])) {
        $isAdmin = 1;
    } else {
        $isAdmin = 0;
    }

    $image = "";

    if ($_FILES['image']['size'] > 0) {
        $image = retourneImage();

        if ($image == "") {
            return messageErreur("Erreur dans l'image");
        }
    }

    $monacces->insererEmploye(null, $prenom, $nomfamille, $username, $password, $couleur, $retourFormulaire['email'], $isAdmin, $image);

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

function cropImage($uploadfile, $uploaddir, $type)
{
    $targ_w = 300;
    $targ_h = 400;
    $jpeg_quality = 100;
    $output_filename = $uploaddir . "cropped.jpg";

    if (strpos($type, 'jpeg') !== false) {
        $img_r = imagecreatefromjpeg($uploadfile);
    } else {
        if (strpos($type, 'gif') !== false) {
            $img_r = imagecreatefromjpeg($uploadfile);
        } else {
            $img_r = imagecreatefrompng($uploadfile);
        }
    }

    $dst_r = ImageCreateTrueColor($targ_w, $targ_h);

    imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x'], $_POST['y'],
        $targ_w, $targ_h, $_POST['w'], $_POST['h']);

    imagejpeg($dst_r, $output_filename, $jpeg_quality);

    return $output_filename;
}
