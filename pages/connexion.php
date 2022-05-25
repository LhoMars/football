<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Connexion</title>
    <?php
    require_once "../php/includeAll.php";
    loggedInRedirect();

    if (isset($_POST['validSubmit']) && isset($_POST['email']) && isset($_POST['password'])) {
        $t = loginUser($_POST['email'], $_POST['password']);
        $infoUser = getUserWithEmailAddress($_POST['email']);
        if ($t) {
            header("location:" . INCLUDE_DIR . "index.php");
        }
    }

    if (isset($_POST['email'])) {
        $email = $_POST['email'];
    } else {
        $email = '';
    }
    ?>
</head>
<body>
<?php
include_once(ROOT_PATH . 'pages/header.php');
?>
<div class="form-body">
    <div class="row">
        <div class="form-holder">
            <div class="form-content">
                <div class="form-items">
                    <div id="preview"></div>
                    <h3>Se connecter</h3>
                    <p>Saisissez le formulaire</p>

                    <form id="formulaire" name="formulaire" action="" method="post"
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
                            <button id="validSubmit" type="submit" name="validSubmit" class="btn btn-primary">
                                Se connecter
                            </button>
                            <span id="errorMesage" style="color: red; text-align: center;">
                                        Identifiant ou mot de passe inconnue
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
</script>
<?php
if (isset($_GET['connect'])) {
    ?>
    <script>divErrorMessage.style.display = "contents";</script>
    <?php
}
?>
</body>
</html>