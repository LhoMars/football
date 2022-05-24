<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Connexion</title>
    <?php
    require_once "../php/includeAll.php";

    //admin redirection


    $generateSaison = null;
    $generateRencontres = null;
    if (isset($_GET['generateSaison'])) {
        $generateSaison = $_GET['generateSaison'];
    }
    if (isset($_GET['generateRencontres'])) {
        $generateRencontres = $_GET['generateRencontres'];
    }
    if (isset($_GET['RencontreExiste'])) {
        $rencontreExiste = $_GET['RencontreExiste'];
        if ($rencontreExiste == "true") {
            ?>
            <script>alert("ça marche")</script> <?php
        } else { ?>
            <script>alert("ça marche pas")</script> <?php
        }

    }
    ?>
    <!DOCTYPE html>
    <!--
    To change this license header, choose License Headers in Project Properties.
    To change this template file, choose Tools | Templates
    and open the template in the editor.
    -->
    <html>
    <script src="JS/script.js"></script>

    <head>
        <meta charset="UTF-8">
        <title>Admin Dashboard</title>

        <link href="CSS/style.css" rel="stylesheet" type="text/css"/>

    </head>

<body>
<?php
include_once(ROOT_PATH . 'pages/header.php');
?>
<h1>Admin Dashboard</h1>
<div class="dashboard">
    <div class="dashboard-grid">
        <div class="dashboard-container">
            <h2>Initialisation de saison</h2>
            <form action="envoyerSaison.php" method="POST">
                <label for="createSaisonChamp" id="labelCreateSaisonChamp">Championnat</label>
                <input id="createSaisonChamp" name="createSaisonChamp" value="1"><br>
                <label for="createSaisonAnnee" id="labelCreateSaisonAnnee">annee saison</label>
                <input id="createSaisonAnnee" name="createSaisonAnnee" value="<?= date("Y") ?>"><br>
                <input type="submit" value="Valider">
            </form>
            <p>Résutat : <?= $generateSaison ?> </p>
        </div>
        <div class="dashboard-container">
            <h2>Initialisation de rencontres</h2>
            <form action="envoyerRencontre.php" method="POST">
                <label for="createRencontreChamp" id="labelCreateRencontreChamp">Championnat</label>
                <input id="createRencontreChamp" name="createRencontreChamp" value="1"><br>
                <label for="createRencontreDate" id="labelCreateRencontreDate">date du premier match</label>
                <input id="createRencontreDate" name="createRencontreDate" type="date" value="<?= date("Y-m-d") ?>"><br>
                <input type="submit" value="Valider">
            </form>
            <p>Résutat : <?= $generateRencontres ?> </p>
        </div>
        <div class="dashboard-container">
            <h2>Saisie des scores</h2>
            <form class="rencontreForm" action="envoyerScoreManuel.php" method="POST">

                <!-- <select id="rencontre" name="rencontre" onChange="changeRencontre(this.value)">
                        <option value="0">Choisir une rencontre</option>
                        <?php
                // foreach (getRencontresData($cnx) as $data) {
                //     getRencontresIntoSelect($cnx);
                // }
                ?>
                    </select> -->
                <div class="scoresManuel">
                    <h3 id="idRencontre">Rencontre</h3>
                    <div class="score1 score-container">
                        <select id="dateSelectRencontre" name="date">
                            <?php
                            getDatesRencontre();
                            ?>
                        </select>
                        <select name="selectEquipe1" id="selEquipe1">
                            <option value="0">Choisir une équipe Domicile</option>
                            <?php
                            getClubIntoSelect();
                            ?>
                        </select><br><br>
                        <input type="number" name="score1" id="score1" class="scoreInput" value="0">
                    </div>
                    <div class="score-container">
                        <select name="selectEquipe2" id="selEquipe2">
                            <option value="0">Choisir une équipe Visiteur</option>
                            <?php
                            getClubIntoSelect();
                            ?>
                        </select><br><br>
                        <input type="number" name="score2" id="score2" class="scoreInput" value="0">
                    </div>
                    <input type="submit" value="Valider">
                </div>
            </form>
        </div>
        <div class="dashboard-container">
            <h2>Saisie des scores automatique</h2>
            <div class="scoreAuto">
                <form action="envoyerScoreAuto.php" method="POST">
                    <select id="dateSelectRencontre" name="date">
                        <?php
                        getDatesRencontre();
                        ?>
                    </select>
                    <input type="submit" value="Valider">
                </form>
            </div>
        </div>
    </div>
</div>
</body>

</html>