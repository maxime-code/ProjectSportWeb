<?php
    session_start();
    session_destroy();
    header("Location: ../html/authentification.html");
    exit();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Déconnexion</title>
    </head>
    <body>
    </body>
</html>