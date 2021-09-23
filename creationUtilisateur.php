<html>
    <head>
        <title>Création utilisateur</title>
        <?php
        include_once 'includeAll.php';
        getDatabaseConnection();
        ?>
    </head>
    <body>
        <form action="enregistrementUtilisateur">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputNom">Nom</label>
                    <input type="text" class="form-control" id="inputNom" placeholder="entrer votre nom">
                </div>
                <div class="form-group col-md-6">
                    <label for="inputPrenom">Prenom</label>
                    <input type="text" class="form-control" id="inputPrenom" placeholder="entrer votre prenom">
                </div>
                <div class="form-group col-md-6">
                    <label for="inputEmail">Email</label>
                    <input type="email" class="form-control" id="inputEmail" placeholder="saisir votre email">
                </div>
                <div class="form-group col-md-6">
                    <label for="inputPassword">Mot de passe</label>
                    <input type="password" class="form-control" id="inputPassword" placeholder=" sasir votre mot de passe">
                </div>
                <div class="form-group">
                    <label for="inputAdresse">Adresse</label>
                    <input type="text" class="form-control" id="inputAdresse" placeholder="saisir le numéro de la rue et la rue">
                </div>
                <div class="form-group col-md-6">
                    <label for="inputVille">Ville</label>
                    <input type="text" class="form-control" id="inputVille" placeholder="saisir votre ville">
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="sexe" id="sexeHomme" value="H" checked>
                    <label class="form-check-label" for="sexeHomme">Homme</label>
                    <input class="form-check-input" type="radio" name="sexe" id="sexeFemme" value="F">
                    <label class="form-check-label" for="sexeFemme">Femme</label>
                </div>
                 <div class="col-auto my-1">
                    <label class="mr-sm-2" for="inlineFormCustomSelect">Preference</label>
                    <select class="custom-select mr-sm-2" id="inlineFormCustomSelect">
                      <option selected>Choose...</option>
                      <option value="1"><?="PHP action"?></option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">S'enregistrer</button>
            </div>
        </form>
    </body>
</html>