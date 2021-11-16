<?php
require_once "function.php";

switch ($_GET['action']):

    case'voirArticle':
        if ($_POST['id_article'] > 0) {
            echo renderCommentaire($_POST['id_article']);
        } else {
            echo 'erreur de chargement des commentaires';
        }
        break;

    case'enregistrementCom':
        if (isset($_POST['idArticle']) && isset($_POST['nom']) && isset($_POST['text'])) {
            addCommentaire($_POST['idArticle'], $_POST['nom'], $_POST['text']);
        }
        break;

    default:
        break;

endswitch;

?>