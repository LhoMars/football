<html>
    <head>
        <title>Inscription</title>
        <?php
        include_once 'php/includeAll.php';
        getDatabaseConnection();
        $clubs = getDataFromDataBase("club");
        ?>
        <link href="<?= INCLUDE_DIR ?>/style/formulaire.css" rel="stylesheet" type="text/css"/>
        <style>
            #clubFavoris{
                display: none;
            }
        </style>
    </head>

    <div class="form-body">
        <div class="row">
            <div id="preview" style="width: 25%; position: absolute; margin: 100px; box-sizing: border-box;">

            </div>
            <div class="form-holder">

                <div class="form-content">
                    <div class="form-items">
                        <h3>S'inscrire maintenant</h3>
                        <p>Saisissez le formulaire</p>
                        <form name="formulaire" action="" method="post" autocomplete="off">

                            <div class="col-md-12">
                                <input id="nom" class="form-control" name="nom" type="text" placeholder="Nom">
                            </div>

                            <div class="col-md-12">
                                <input id="prenom" class="form-control" name="Prenom" type="text" placeholder="Prenom">
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
                                <input id="clubFavori" list="clubFavoris" name="clubFavori">
                                <datalist  id="clubFavoris" class="custom-select form-select mt-3"  name="clubFavoris">
                                    <option selected disabled value="">Selectionner</option>
                                    <?php
                                    $i = 1;
                                    foreach ($clubs as $club) {
                                        ?>
                                        <option value="<?= $i ?>" name="<?= $club['nom_club'] ?>"><?= $club['nom_club'] ?></option>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                                </datalist>
                                <script>
                                    var values = [];
                                    $('#clubFavoris option').each(function () {
                                        values.push($(this).name());
                                    });
                                </script>
                            </div>
                            <div class="col-md-12">
                                <div class="scrollingdiv">
                                    <label for="clubNews"> Choissisez vos news d'équipe  :</label>
                                    <div>
                                        <select id="clubNews" class="image-picker" multiple name="clubNews[]">
                                            <?php
                                            $i = 1;
                                            foreach ($clubs as $club) {
                                                ?>
                                                <option style="background-color:#152733"data-img-src="asset/club/<?= $club['nom_club'] ?>2.png" value="<?= $i ?>"><?= $club['nom_club'] ?></option>
                                                <?php
                                                $i++;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <script>
                                $("#clubNews").imagepicker()
                            </script>
                            <div>
                                <label for="avatar">Choisir une photo de profile :</label>
                                <input id="avatar" class="form-control" type="file"  name="avatar" accept="image/png, image/jpg, image/jpeg">
                            </div>

                            <div class="form-button mt-3">
                                <button id="submit" type="submit" class="btn btn-primary">S'inscrire</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    if (isset($_POST)) {
        ?>
        <div style="background-color: #fff"><?php dump($_POST); ?></div>
        <?php
    }
    ?>
</body>
<script>

    var input = document.querySelector('#avatar');
    var preview = document.querySelector('#preview');
    input.addEventListener('change', updateImageDisplay);

</script>
</html>