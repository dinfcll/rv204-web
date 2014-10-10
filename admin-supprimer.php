<?php

include "admin-header.php" ;

$employeDao->deleteById($_GET['id']);

header('Location: admin.php');