<?php
include "accesbd.class.php";

$monacces = new AccesBD();

$image = $monacces->genererImage($_GET['id']);

header('Content-Type: image/png');

echo $image;