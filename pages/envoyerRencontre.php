<!DOCTYPE html>
<?php
require_once "../php/includeAll.php";
notAdminRedirect();

$res = false;

if (isset($_POST['createRencontreChamp']) && isset($_POST['createRencontreDate'])) {

    $championnat = (int) $_POST['createRencontreChamp'];
    $date = $_POST['createRencontreDate'];

    if(is_int($championnat)){
        $res = createRencontres($championnat, $date);
    }

}
$res = $res ? 'true' : 'false';
header('Location:'.INCLUDE_DIR.'pages/dashboard.php?generateRencontres='.$res);
?>