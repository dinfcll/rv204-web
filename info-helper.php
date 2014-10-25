<?php

include_once "accesbd.class.php";

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

function maj(Employe $employeCourant)
{
    $couleur = $_POST['couleur'];
    $password = $employeCourant->getPassword();

    if ($_POST['password1'] != "") {
        if ($_POST['password1'] == $_POST['password2']) {
            $password = password_hash($_POST['password1'], PASSWORD_DEFAULT);
        } else {
            return messageErreur("Les mots de passe sont différents");
        }
    }

    $image = $employeCourant->getPicture();

    if ($_FILES['image']['error'] == 1) {
        return messageErreur("L'image est trop pesante. Quelque chose de moins lourd, peut-être?");
    }

    if ($_FILES['image']['size'] > 0) {
        $image = retourneImage();

        if ($image == "") {
            return messageErreur("Erreur dans l'image");
        }
    }

    $employeCourant->setPassword($password);
    $employeCourant->setColor($couleur);
    $employeCourant->setPicture($image);
    $employeCourant->setEmail($_POST['email']);

    (new EmployeDao())->put($employeCourant);

    return messageSucces("Votre compte a bien été mis à jour");
}

function putUser(Employe $employeCourant, $retourFormulaire)
{
    $image = "";
    $id = null;
    $couleur = "";

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

    if ($retourFormulaire['password1'] == "") {
        $password = $employeCourant->getPassword();
    } else {
        if ($retourFormulaire['password1'] == $retourFormulaire['password2']) {
            $password = password_hash($retourFormulaire['password1'], PASSWORD_DEFAULT);
        } else {
            return messageErreur("Les mots de passe sont différents");
        }
    }

    if (isset($retourFormulaire['admin'])) {
        $isAdmin = 1;
    } else {
        $isAdmin = 0;
    }

    if (isset($retourFormulaire['rpi'])) {
        $hasRpi = 1;
    } else {
        $hasRpi = 0;
    }

    $employeDao = new EmployeDao();

    if (isset($retourFormulaire['id'])) {
        $id = $retourFormulaire['id'];

        $image = $employeDao->getById($retourFormulaire['id'])->getPicture();
    }

    if ($_FILES['image']['size'] > 0) {
        $image = retourneImage();

        if ($image == "") {
            return messageErreur("Erreur dans l'image");
        }
    }

    if ($hasRpi) {
        if (isset($retourFormulaire['couleur']) && $retourFormulaire['couleur'] != "") {
            $couleur = $retourFormulaire['couleur'];
        } else {
            return messageErreur("Vous devez choisir une couleur");
        }
    }


    $employeDao->put((new EmployeBuilder())
        ->firstName($prenom)
        ->lastName($nomfamille)
        ->username($username)
        ->password($password)
        ->color($couleur)
        ->email($retourFormulaire['email'])
        ->isAdmin($isAdmin)
        ->picture($image)
        ->id($id)
        ->hasRpi($hasRpi)
        ->build());

    return messageSucces("L'opération a été effectuée avec succès.");
}

function messageErreur($message)
{
    return message($message, "danger");
}

function messageSucces($message)
{
    return message($message, "success");
}

function messageInfo($message)
{
    return message($message, "info");
}

function message($message, $class)
{
    return "<div class=\"alert alert-" . $class . "\">" . $message . "</div>";
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

function majAdmin($retourFormulaire) {
    $adresse = trim($retourFormulaire['rpiAddress']);
    $longueur = strlen($adresse);

    if($adresse[$longueur-1] != "0") {
        return messageErreur("Adresse invalide");
    }

    if($retourFormulaire['mailgunDomain'] == "") {
        return messageErreur("Veuillez entrer un domaine Mailgun");
    }

    if($retourFormulaire['mailgunApiKey'] == "") {
        return messageErreur("Veuillez entrer une clé d'API Mailgun");
    }

    $monacces = new AccesBD();
    $monacces->modifierRpiNetwork(substr($adresse, 0, -1));
    $monacces->modifierMailgunDomain($retourFormulaire['mailgunDomain']);
    $monacces->modifierMailgunApiKey($retourFormulaire['mailgunApiKey']);

    return messageSucces("Changement de configuration effectué.");
}
