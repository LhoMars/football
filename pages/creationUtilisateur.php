<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Inscription</title>
    <?php
    require_once "../php/includeAll.php";
    loggedInRedirect();
    getDatabaseConnection();
    $clubs = getDataFromDataBase("club");
    ?>
</head>
<body>
<?php
if (isset($_POST['validSubmit'])) {

    // Vérifie que le boutton à été cliquer
    // Affichade de variable

    if (preg_match('/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/', $_POST['email'])
        && preg_match('/[a-zA-ZçéèêëíìîïôöÿæœÇÉÈÊËÎÏÔÖÛÜŸ]{3,}/', $_POST['nom'])
        && preg_match('/[a-zA-ZçéèêëíìîïôöÿæœÇÉÈÊËÎÏÔÖÛÜŸ]{3,}/', $_POST['prenom'])
        && $_POST['clubFavori'] != 0
        && preg_match("#homme|femme#", $_POST['sexe'])
        && password_verify($_POST['password'], password_hash($_POST['password2'], PASSWORD_DEFAULT))) {

        $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        //dump($_POST);

        // enregistre l'utilisateur
        $id = signUser($_POST);

        // Vérifie si l'utilisateur à mis un avatar
        if (strcmp($_FILES['avatar']['name'], '')) {
            updateAvatar($id, $_FILES['avatar']);
        }

        // Vérifie si l'utilisateur à choisi des abbonement au club
        if (isset($_POST['clubNews'])) {
            subscribeClub($id, $_POST['clubNews']);
        }
        $userInfo = getUserWithEmailAddress(trim($_POST['email']));
        createSession($userInfo);
        //header("location:" . INCLUDE_DIR . "index.php");
    }
}
if ($_POST) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
} else {
    $nom = '';
    $prenom = '';
    $email = '';
}
include_once(ROOT_PATH . 'pages/header.php'); ?>
    <div class="row">
        <div class="form-holder">
            <div class="form-content">
                <div class="form-items">
                    <div id="preview"></div>
                    <h3>S'inscrire maintenant</h3>
                    <p>Saisissez le formulaire</p>

                    <form id="formulaire" name="formulaire" enctype="multipart/form-data" action="" method="post"
                          autocomplete="off">

                        <div class="col-md-12">
                            <input id="nom" class="form-control" name="nom" type="text" placeholder="nom"
                                   value="<?= $nom ?>" required="">
                        </div>

                        <div class="col-md-12">
                            <input id="prenom" class="form-control" name="prenom" type="text" placeholder="prenom"
                                   value="<?= $prenom ?>" required="">
                        </div>

                        <div class="col-md-12">
                            <input id="email" class="form-control" name="email" type="email" placeholder="E-mail"
                                   title="example@domain.fr" value="<?= $email ?>" required="">
                        </div>

                        <div class="col-md-12">
                            <input id="password" class="form-control" name="password" type="password"
                                   placeholder="Saisir votre mot de passe" required="">
                        </div>

                        <div class="col-md-12">
                            <input id="password2" class="form-control" name="password2" type="password"
                                   placeholder="Saisir votre mot de passe une deuxième fois" required="">
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="mb-3 mr-1" for="sexe">Sexe : </label>

                            <input id="homme" class="btn-check" type="radio" name="sexe" value="homme" checked>
                            <label class="btn btn-sm btn-outline-secondary" for="homme">Homme</label>

                            <input id="femme" class="btn-check" type="radio" name="sexe" value="femme">
                            <label class="btn btn-sm btn-outline-secondary" for="femme">Femme</label>
                        </div>

                        <div class="col-md-12">
                            <label for="clubFavori"> Choissisez une équipe favorite :</label>
                            <select id="clubFavori" list="clubFavoris" name="clubFavori" required="">
                                <option value="0">Selection</option>
                                <?php
                                foreach ($clubs as $club) {
                                    ?>
                                    <option value="<?= $club['id_club'] ?>"><?= $club['nom_club'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <div class="scrollingdiv">
                                <label for="clubNews"> Choissisez vos news d'équipe :</label>
                                <div>
                                    <select id="clubNews" class="image-picker" multiple name="clubNews[]">
                                        <?php
                                        foreach ($clubs as $club) {
                                            ?>
                                            <option style="background-color:#152733"
                                                    data-img-src="<?= INCLUDE_DIR ?>asset/club/<?= $club['icon_club'] ?>.png"
                                                    value="<?= $club['id_club'] ?>"><?= $club['nom_club'] ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <script>
                                        $("#clubNews").imagepicker();
                                    </script>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="avatar">Choisir une photo de profil :</label>
                            <input id="avatar" class="form-control" type="file" name="avatar"
                                   accept="image/png, image/jpg, image/jpeg, image/gif">
                        </div>
                        <script>
                            var input = document.querySelector('#avatar');
                            var preview = document.querySelector('#preview');
                            input.addEventListener('change', updateImageDisplay);
                        </script>
                        <div class="form-button mt-3">
                            <button id="validSubmit" type="submit" name="validSubmit" class="btn btn-primary">
                                S'inscrire
                            </button>
                            <span id="errorMesage" style="color: red; text-align: center;">
                                        Erreur dans la saisie du formulaire
                                    </span>
                        </div>
                    </form>
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

        var nom = (monForm["nom"]) ? monForm["nom"] : "";
        var prenom = (monForm["prenom"]) ? monForm["prenom"] : "";
        var email = (monForm["email"]) ? monForm["email"] : "";
        var mdp = (monForm["mdp"]) ? monForm["mdp"] : "";

        var emailPregMatch = /^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/.test(email.value);
        var nomPregMatch = /[a-zA-ZçéèêëíìîïôöÿæœÇÉÈÊËÎÏÔÖÛÜŸ]{3,}/.test(nom.value);
        var prenomPregMatch = /[a-zA-ZçéèêëíìîïôöÿæœÇÉÈÊËÎÏÔÖÛÜŸ]{3,}/.test(prenom.value);

        if (emailPregMatch && nomPregMatch && prenomPregMatch) {
            divErrorMessage.style.display = "none";
        } else {
            event.preventDefault();
            divErrorMessage.style.display = "contents";
        }
    });
</script>
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