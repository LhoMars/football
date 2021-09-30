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
                                <input id="nom" class="form-control" name="nom" type="text" placeholder="Nom">
                            </div>

                            <div class="col-md-12">
                                <input id="prenom" class="form-control" name="prenom" type="text" placeholder="prenom">
                            </div>

                            <div class="col-md-12">
                                <input id="email" class="form-control" name="email" type="email" placeholder="E-mail">
                            </div>                                

                            <div class="col-md-12">
                                <input id="password" class="form-control" name="password" type="password"  placeholder="Saisir votre mot de passe">
                            </div>

                            <div class="col-md-12 mt-3">
                                <label class="mb-3 mr-1" for="sexe">Sexe : </label>

                                <input id="homme" class="btn-check" type="radio" name="sexe" value="homme">
                                <label class="btn btn-sm btn-outline-secondary" for="homme">Homme</label>

                                <input id="femme" class="btn-check"type="radio"  name="sexe" value="femme">
                                <label class="btn btn-sm btn-outline-secondary" for="femme">Femme</label>
                            </div>      

                            <div class="col-md-12">
                                <label for="clubFavori"> Choissisez une équipe favorite :</label>
                                <select id="clubFavori" list="clubFavoris" name="clubFavori">
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
                                <button id="submit" type="submit" name="submit" class="btn btn-primary">S'inscrire</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="color:#fff;">
        <?php
//        // Vérifie que le boutton à été cliquer
        if (isset($_POST['submit'])) {
//            // Affichade de variable
            dump($_POST);
            updateUtilisateur('1', $_POST);
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