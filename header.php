<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">

    <title>RV204 - web</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">

    <link href="css/style.css" rel="stylesheet">
</head>

<body>
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="info.php">RV204</a>
        </div>
        <div class="collapse navbar-collapse">
            <?php
            if ($utilisateur['isAdmin'] != 0) {
                echo '
                        <ul class="nav navbar-nav">
                            <li><a href="admin.php">Admin</a></li>
                        </ul>
                      ';
            }
            ?>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="disconnect.php">Se déconnecter</a></li>
            </ul>
        </div>
    </div>
</div>