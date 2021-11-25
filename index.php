<!DOCTYPE html>
<html>
    <head>
        <title>Accueil</title>
        <?php
        require_once "php/includeAll.php";
        ?>
    </head>
    <body>
    <?php
     include_once(ROOT_PATH.'pages/header.php');?>
        <div class="container" style="color:#fff">
            <h3>Session :</h3>
            <?php dump($_SESSION);?>
            <h3>Serveur :</h3>
            <?php dump($_SERVER); ?>
        </div>
    </body>
</html>