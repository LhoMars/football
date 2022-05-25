<!DOCTYPE html>
<?php
require_once "../php/includeAll.php";
notAdminRedirect();

$res = false;

if (isset($_POST['createSaisonChamp']) && isset($_POST['createSaisonAnnee'])) {
    $championnat = (int) $_POST['createSaisonChamp'];
    $annee = (int) $_POST['createSaisonAnnee'];

    if(is_int($championnat) && is_int($annee)){
        $res = createSaison($championnat, $annee);
    }

}
$res = $res ? 'true' : 'false';
header('Location:'.INCLUDE_DIR.'pages/dashboard.php?generateSaison='.$res);
?>