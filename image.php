<?php
include "accesbd.php";

$monacces = new AccesBD();

$image = $monacces->genererImage($_GET['id']);

header('Content-Type: image/png');

echo $image;