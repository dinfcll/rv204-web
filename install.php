<?php

include_once('accesbd.class.php');

$monAcces = new AccesBD();
if ($monAcces->applicationNonInstallee()) {
    $monAcces->creerTableEmployes();
    $monAcces->creerUsagersStandards();
}

if($monAcces->pasDeConfig()) {
    $monAcces->creerConfigurations();
}
