<?php

include_once('accesbd.php');

$monAcces = new AccesBD();
if ($monAcces->applicationNonInstallee()) {
    $monAcces->creerTableEmployes();
    $monAcces->creerAdministrateur();
    $monAcces->creerOlivier();
    $monAcces->creerGuillaume();
}
