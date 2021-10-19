<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Connexion</title>
    <?php
    include_once "../php/includeAll.php";
    loggedInRedirect();

    if (isset($_POST['validSubmit'])) {
        $t = loginUser($_POST['email'],$_POST['password']);
        if($t){
            header("location:".INCLUDE_DIR."index.php");
        }else {
            header("Location:connexion.php?connect=false");
        }
    }

    if ($_POST) {
        $email = $_POST['email'];
    } else {
        $email = '';
    }
    ?>
</head>
<body>
<div class="form-body">
    <div class="row">
        <div class="form-holder">
            <div class="form-content">
                <div class="form-items">
                    <div id="preview"></div>
                    <h3>Se connecter</h3>
                    <p>Saisissez le formulaire</p>

                    <form id="formulaire" name="formulaire" enctype="multipart/form-data" action="" method="post"
                          autocomplete="off">

                        <div class="col-md-12">
                            <input id="email" class="form-control" name="email" type="email" placeholder="E-mail"
                                   title="example@domain.fr" value="<?= $email ?>" required="">
                        </div>

                        <div class="col-md-12">
                            <input id="password" class="form-control" name="password" type="password"
                                   placeholder="Saisir votre mot de passe" required="">
                        </div>
                        <div class="form-button mt-3">
                        <button id="validSubmit" type="submit" name="validSubmit" class="btn btn-primary">S'inscrire</button>
                        <span id="errorMesage" style="color: red; text-align: center;">
                                        Erreur dans la saisie du formulaire
                                    </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var divErrorMessage = document.getElementById("errorMesage");
    divErrorMessage.style.display = "none";

    document.querySelector("#formulaire").addEventListener("submit", function (event) {
        var monForm = document.getElementById("formulaire");
        var divErrorMessage = document.getElementById("errorMesage");

        var email = (monForm["email"]) ? monForm["email"] : "";

        var emailPregMatch = /^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/.test(email.value);

        if (emailPregMatch) {
            divErrorMessage.style.display = "none";
        } else {
            event.preventDefault();
            divErrorMessage.style.display = "contents";
        }
    });
</script>
<?php
if(isset($_GET['connect'])){?>
<script>divErrorMessage.style.display = "contents";</script>
<?php
}
?>
</body>
</html>