<?php
include "accesbd.class.php";
include "info-helper.php";

session_start();

$employeCourant = (new EmployeBuilder())->build();

if (utilisateurConnecte()) {
    $employeCourant = (new EmployeDao())->getByUsername($_SESSION['nom_utilisateur']);

    if ($employeCourant->isAdmin() == 0) {
        header('Location: info.php');
    }

} else {
    header('Location: index.php');
}
