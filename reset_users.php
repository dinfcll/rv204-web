<?php
include_once('accesbd.php');

$monacces = new AccesBD();

$monacces->supprimerTable();
$monacces->creerTableEmployes();
$monacces->creerUsagersStandards();

header('Location: index.php');