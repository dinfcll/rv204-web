<?php

include "admin-verification.php";

(new EmployeDao())->deleteById($_GET['id']);

header('Location: admin.php');