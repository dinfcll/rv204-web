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

<!-- TODO: Valider l'utilisateur -->

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.html">RV204</a>
        </div>
    </div>
</div>

<div class="container">
    <div class="page-header">
        <h1>Olivier Lafleur</h1> <!-- TODO: Requête pour récupérer le nom -->
    </div>

    <form>
        <p>
            <label>
                Choisissez votre couleur préférée:
                <!-- TODO: Requête pour récupérer la couleur -->
                <input type="color" name="couleurpreferee">
            </label>
        </p>

        <button type="submit" class="btn btn-primary">Modifier</button>
    </form>

</div>

<!-- Bootstrap et jQuery -->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>
