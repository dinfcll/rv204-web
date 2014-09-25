<?php

include "admin-header.php" ;

$monacces->supprimerUsager($_GET['id']);

header('Location: admin.php');