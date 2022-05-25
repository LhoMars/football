<?php

session_start(); //to ensure you are using same session

include_once './gestionBD.php';

//send scores entered in dashboard to database
if (isset($_POST['date']) && isset($_POST['score1']) && isset($_POST['score2']) && isset($_POST['selectEquipe1']) && isset($_POST['selectEquipe2'])) {
    $date = $_POST['date'];
    $equipe1 = $_POST['selectEquipe1'];
    $equipe2 = $_POST['selectEquipe2'];
    $score1 = $_POST['score1'];
    $score2 = $_POST['score2'];
    //update rencontre
    $rencontreExiste = setScoreRencontreManuel($equipe1,$equipe2,$date,$score1,$score2);
}

//location: dashboard.php
?>
<?php
//var_dump($rencontreExiste[0]['set_scorerencontre']);
$res = $rencontreExiste[0]['set_scorerencontre']? "true" : "false";
header('Location:'.INCLUDE_DIR.'pages/dashboard.php?RencontreExiste='.$res);