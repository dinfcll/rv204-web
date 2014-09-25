<?php
include "accesbd.php";
include "info-helper.php";

session_start();

$utilisateur = "";
$monacces = new AccesBD();

if (utilisateurConnecte()) {
    $utilisateur = $monacces->recupererUtilisateur($_SESSION['nom_utilisateur']);

    if ($utilisateur['isAdmin'] == 0) {
        header('Location: info.php');
    }

} else {
    header('Location: index.php');
}

include "header.php";
