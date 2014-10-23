<?php

include_once('accesbd.class.php');

$monAcces = new AccesBD();

define('RPI_IP_BEGINNING_ADRESS', $monAcces->getRpiNetwork());
define('RPI_PORT', '9999');
