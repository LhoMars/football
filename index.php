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
        <div style="color:#fff">
            <?php dump($_SESSION);
            dump($_SERVER); ?>
        </div>
    </body>
</html>