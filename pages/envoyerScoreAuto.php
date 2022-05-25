<?php

session_start(); //to ensure you are using same session

include_once './gestionBD.php';

//send scores entered in dashboard to database
//update rencontre
if (isset($_POST['dateSelectRencontre'])) {
    $date = $_POST['dateSelectRencontre'];
    $statement = "SELECT setScoresAuto($date)";
    $c->exec($statement);
    if ($c) {
        echo "Score enregistré";
    } else {
        echo "Erreur lors de l'enregistrement du score";
    }
}

//location: dashboard.php
header('Location:'.INCLUDE_DIR.'pages/dashboard.php');
