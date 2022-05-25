<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Admin Dashboard</title>
    <?php
    require_once "../php/includeAll.php";

    //admin redirection
    notAdminRedirect();

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
    </div>
</div>
</body>

</html>