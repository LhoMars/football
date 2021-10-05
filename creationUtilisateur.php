<html>
    <head>
        <title>Inscription</title>
        <?php
        include_once 'php/includeAll.php';
        getDatabaseConnection();
        $clubs = getDataFromDataBase("club");
        ?>
        <link href="<?= INCLUDE_DIR ?>style/formulaire.css" rel="stylesheet" type="text/css"/>
    </head>
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
                                <input id="nom" class="form-control" name="nom" type="text" placeholder="nom" pattern="[a-zA-Z]{3,}" required>
                            </div>

                            <div class="col-md-12">
                                <input id="prenom" class="form-control" name="prenom" type="text" placeholder="prenom" pattern="[a-zA-Z]{3,}" required>
                            </div>

                            <div class="col-md-12">
                                <input id="email" class="form-control" name="email" type="email" placeholder="E-mail" title="example@domain.fr" required>
                            </div>                                

                            <div class="col-md-12">
                                <input id="password" class="form-control" name="password" type="password"  placeholder="Saisir votre mot de passe" required>
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
                                <button id="submit" type="submit" name="validSubmit" class="btn btn-primary">S'inscrire</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var boutonSubmit = document.getElementById("submit");

        boutonSubmit.onclick = function (event) {

            var monForm = document.getElementById("formulaire");
//    var monForm = document.forms("formulaire");
            var nom = monForm["nom"];
            var prenom = monForm["prenom"];
            var mail = monForm["email"];
            var sexe = monForm["sexe"];
            var mdp = monForm["password"];

            var emailPregMatch = /^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/.test(email.value);
            var nomPregMatch = /[a-zA-Z]{3,}/.test(nom.value);
            var prenomPregMatch = /[a-zA-Z]{3,}/.test(prenom.value);
            var mdpPregMatch = mdp != "";

            if (emailPregMatch && nomPregMatch && prenomPregMatch && mdpPregMatch) {
                document.write("ok");
            }else{
                event.preventDefault();
            }
        }



    </script>
    <div style="color:#fff;">
        <?php
//        // Vérifie que le boutton à été cliquer
        if (isset($_POST['submit'])) {
//            // Affichade de variable
            dump($_POST);
            // enregistre l'utilisateur
//            $id = signUser($_POST);
//
//            // Vérifie si l'utilisateur à mis un avatar
//            if (isset($_FILES['avatar'])) {
//                $filename = updateAvatar($id, $_FILES['avatar']);
//
//                $uploaddir = './asset/avatarUtilisateur/';
//                $uploadfile = $uploaddir . basename($filename);
////                dump($uploadfile);die;
//                // Déplace le fichier de xamp/temp à l'endroit choisi
//                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadfile)) {
////                    echo "Le fichier est valide, et a été téléchargé
////                    avec succès. Voici plus d'informations :\n";
////                } else {
////                    echo "Attaque potentielle par téléchargement de fichiers.
////                    Voici plus d'informations :\n";
//                }
////                echo 'Voici quelques informations de débogage :';
//                dump($_FILES);
//            }
//
//            // Vérifie si l'utilisateur à choisi des abbonement au club
//            if (isset($_POST['clubNews'])) {
//                subscribeClub($id, $_POST['clubNews']);
//        }
            ?>
        </div>
        <?php
    }
    ?>
</body>
</html>