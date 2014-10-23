<?php
include_once('accesbd.class.php');

$monacces = new AccesBD();

$monacces->supprimerTables();
$monacces->creerTableEmployes();
$monacces->creerUsagersStandards();

header('Location: index.php');