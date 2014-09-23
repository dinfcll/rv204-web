<?php

include_once('accesbd.php');

$monAcces = new AccesBD();
$monAcces->creerTableEmployes();
$monAcces->creerAdministrateur();
$monAcces->creerOlivier();