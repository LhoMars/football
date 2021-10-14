<html>
    <head>
        <title>Accueil</title>
        <?php
        include_once "php/includeAll.php";
        ?>
    </head>
    <body>
        <div style="color:#fff">
            <a href="creationUtilisateur.php">create User</a>
            <?php dump($_SESSION);
            dump($_SERVER); ?>
        </div>
    </body>
</html>