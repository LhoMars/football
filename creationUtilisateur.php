<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Inscription</title>
        <?php
        include_once 'php/includeAll.php';
        getDatabaseConnection();
        $clubs = getDataFromDataBase("club");
        ?>
        <link href="<?= INCLUDE_DIR ?>style/formulaire.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?php
        if ($_POST) {
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
        } else {
            $nom = '';
            $prenom = '';
            $email = '';
        }
        ?>
        <div class="form-body">
            <div class="row">
                <div class="form-holder">
                    <div class="form-content">
                        <div class="form-items">                        
                            <div id="preview"></div>
                            <h3>S'inscrire maintenant</h3>
                            <p>Saisissez le formulaire</p>

                            <form id="formulaire" name="formulaire" enctype="multipart/form-data" action="" method="post" autocomplete="off">

                                <div class="col-md-12">
                                    <input id="nom" class="form-control" name="nom" type="text" placeholder="nom" value="<?= $nom ?>" required="">
                                </div>

                                <div class="col-md-12">
                                    <input id="prenom" class="form-control" name="prenom" type="text" placeholder="prenom" value="<?= $prenom ?>" required="">
                                </div>

                                <div class="col-md-12">
                                    <input id="email" class="form-control" name="email" type="email" placeholder="E-mail" title="example@domain.fr" value="<?= $email ?>" required="">
                                </div>                                

                                <div class="col-md-12">
                                    <input id="password" class="form-control" name="password" type="password"  placeholder="Saisir votre mot de passe" required="">
                                </div>

                                <div class="col-md-12 mt-3">
                                    <label class="mb-3 mr-1" for="sexe">Sexe : </label>

                                    <input id="homme" class="btn-check" type="radio" name="sexe" value="homme" checked>
                                    <label class="btn btn-sm btn-outline-secondary" for="homme">Homme</label>

                                    <input id="femme" class="btn-check"type="radio"  name="sexe" value="femme">
                                    <label class="btn btn-sm btn-outline-secondary" for="femme">Femme</label>
                                </div>      

                                <div class="col-md-12">
                                    <label for="clubFavori"> Choissisez une équipe favorite :</label>
                                    <select id="clubFavori" list="clubFavoris" name="clubFavori">
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
                                        <label for="clubNews"> Choissisez vos news d'équipe  :</label>
                                        <div>
                                            <select id="clubNews" class="image-picker" multiple name="clubNews[]">
                                                <?php
                                                foreach ($clubs as $club) {
                                                    ?>
                                                    <option style="background-color:#152733"data-img-src="<?= INCLUDE_DIR ?>asset/club/<?= $club['icon_club'] ?>.png" value="<?= $club['id_club'] ?>"><?= $club['nom_club'] ?></option>
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
                                    <input id="avatar" class="form-control" type="file"  name="avatar" accept="image/png, image/jpg, image/jpeg">
                                </div>
                                <script>
                                    var input = document.querySelector('#avatar');
                                    var preview = document.querySelector('#preview');
                                    input.addEventListener('change', updateImageDisplay);
                                </script>
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
//                var boutonSubmit = document.getElementById("submit");
//
//                boutonSubmit.onsubmit( function (event){

            var divErrorMessage = document.getElementById("errorMesage");
            divErrorMessage.style.display = "none";

            document.querySelector("#formulaire").addEventListener("submit", function (event) {
                var monForm = document.getElementById("formulaire");
                var divErrorMessage = document.getElementById("errorMesage");

//            var nom = monForm["nom"];
//            var prenom = monForm["prenom"];
//            var email = monForm["email"];
////            var sexe = monForm["sexe"];
//            var mdp = monForm["password"];

                var nom = (monForm["nom"]) ? monForm["nom"] : "";
                var prenom = (monForm["prenom"]) ? monForm["prenom"] : "";
                var email = (monForm["email"]) ? monForm["email"] : "";
                var mdp = (monForm["mdp"]) ? monForm["mdp"] : "";

                var emailPregMatch = /^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/.test(email.value);
                var nomPregMatch = /[a-zA-Z]{3,}/.test(nom.value);
                var prenomPregMatch = /[a-zA-Z]{3,}/.test(prenom.value);

                if (emailPregMatch && nomPregMatch && prenomPregMatch) {
//                    alert("ok");
                    divErrorMessage.style.display = "none";
                } else {
                    event.preventDefault();
                    divErrorMessage.style.display = "contents";

                }
            });
        </script>
        <div style="color:#fff;">
            <?php
//        // Vérifie que le boutton à été cliquer
            if (isset($_POST['validSubmit'])) {
//            // Affichade de variable

                if (preg_match('/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/', $_POST['email']) && preg_match('/[a-zA-Z]{3,}/', $_POST['nom']) && preg_match('/[a-zA-Z]{3,}/', $_POST['prenom']) && preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/", $_POST['password']) && $_POST['clubFavori'] != 0) {

                    dump($_POST);
                    // enregistre l'utilisateur
                    $id = signUser($_POST);

                    // Vérifie si l'utilisateur à mis un avatar
                    if (strcmp($_FILES['avatar']['name'], '')) {
                        $filename = updateAvatar($id, $_FILES['avatar']);

                        $uploaddir = './asset/avatarUtilisateur/';
                        $uploadfile = $uploaddir . basename($filename);
//                dump($uploadfile);die;
                        // Déplace le fichier de xamp/temp à l'endroit choisi
                        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadfile)) {
//                    echo "Le fichier est valide, et a été téléchargé
//                    avec succès. Voici plus d'informations :\n";
//                } else {
//                    echo "Attaque potentielle par téléchargement de fichiers.
//                    Voici plus d'informations :\n";
                        }
//                echo 'Voici quelques informations de débogage :';
                        dump($_FILES);
                    }

                    // Vérifie si l'utilisateur à choisi des abbonement au club
                    if (isset($_POST['clubNews'])) {
                        subscribeClub($id, $_POST['clubNews']);
                    }
                }
//            } else {
////                dump('ERREUR');
            }
            ?>
        </div>
    </body>
</html>