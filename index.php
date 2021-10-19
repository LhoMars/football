<html>
    <head>
        <title>Accueil</title>
        <?php
        include_once "php/includeAll.php";
        ?>
    </head>
    <body>
        <div style="color:#fff">
            <a href="<?=INCLUDE_DIR?>pages/creationUtilisateur.php">Inscription</a>
            <a href="<?=INCLUDE_DIR?>pages/connexion.php">Connexion</a>
            <?php dump($_SESSION);
            dump($_SERVER); ?>
        </div>
    </body>
</html>