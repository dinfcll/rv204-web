<?php
include_once('accesbd.class.php');

$monacces = new AccesBD();

$monacces->supprimerTable();
$monacces->creerTableEmployes();
$monacces->creerUsagersStandards();

header('Location: index.php');