<!DOCTYPE html>
<html>
<head>
    <title>Article</title>
    <?php
    require_once "../php/includeAll.php";
    getDatabaseConnection();
    $articles = getDataFromDataBase("article");

    ?>
</head>
<body>
<script type="text/javascript">
    <?php
    $chemin = INCLUDE_DIR;
    echo "var chemin = '{$chemin}';";
    ?>
    const form = "<form id=\"formCom\" name=\"formCom\" action=\"\" autocomplete=\"off\"><div class=\"col-md-12\"><label class=\"mb-3 mr-1\" for=\"nom\">Votre nom : </label> <input id=\"nom\"></div><div class=\"col-md-12\"><label class=\"mb-3 mr-1\" for=\"textCom\">Votre commentaire : </label> <input id=\"textCom\"></div></form>";

    function addCom(id, titre, text) {
        $.confirm(
            {
                useBootstrap: false,
                backgroundDismiss: true,
                boxWidth: '60%',
                title: 'Commentaire',
                content: form,
                buttons:
                    {
                        envoyer: function () {
                            var monForm = document.getElementById("formCom");
                            var nom = (monForm["nom"]) ? monForm["nom"] : "";
                            var textCom = (monForm["textCom"]) ? monForm["textCom"] : "";

                            console.log(nom.value);
                            console.log(textCom.value);

                            var nomPregMatch = /[a-zA-ZçéèêëíìîïôöÿæœÇÉÈÊËÎÏÔÖÛÜŸ]{3,}/.test(nom.value);

                            if (nomPregMatch) {
                                enregistreCom(id, nom.value, textCom.value);
                                showPage(id, titre, text);
                            } else {
                                addCom(id, titre, text);
                                $.confirm(
                                    {
                                        useBootstrap: false,
                                        backgroundDismiss: true,
                                        boxWidth: '60%',
                                        title: 'Commentaire',
                                        content: "information manquante",
                                        buttons:
                                            {
                                                fermer: function () {

                                                }
                                            }
                                    });
                            }
                        },
                        retour: function () {
                            showPage(id, titre, text)
                        }
                    },
            });
    }

    function enregistreCom(idArticle, nom, text) {
        $.ajax(
            {
                url: chemin + 'php/ajax.php?action=enregistrementCom',
                type: 'POST',
                data: {idArticle: idArticle, nom: nom, text: text},
            });
    }

    function showPage(id, titre, text) {

        $.ajax(
            {
                url: chemin + 'php/ajax.php?action=voirArticle',
                type: 'POST',
                data: {id_article: id},
            })
            .done(function (msg) {
                $.confirm(
                    {
                        useBootstrap: false,
                        backgroundDismiss: true,
                        boxWidth: '60%',
                        title: titre,
                        content: text + msg,
                        buttons:
                            {
                                "Ajouter un commentaire": function () {
                                    addCom(id, titre, text)
                                },
                                fermer: function () {

                                }
                            },
                    });
            });
    }
</script>
<?php
include_once(ROOT_PATH . 'pages/header.php'); ?>
<div class="article-body">
    <div class="article-holder">
        <?php foreach ($articles as $article) {
            $id = $article['id_article'];
            $titre = $article['titre_article'];
            $text = $article['text_article'];
            $firstCom = getFirstCom($id);
            ?>
            <div class="article-content">
                <div class="article-items"
                     onclick="showPage(<?= $id ?>,'<h1><?= $titre ?></h1>', '<p><?= $text ?><p>')">
                    <h3><?= $titre ?></h3>
                    <div class=" paragraphe col-md-12">
                        <?= $text ?>
                    </div>
                    <?php if ($firstCom): ?>
                        <h6><?= $firstCom['nom_commentaire'] ?></h6><p><?= $firstCom['text_commentaire'] ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</body>
</html>