# Utilisateur

### Connexion

L'utilisateur à la possibilité de se créer un compte utilisateur et de se connecter au site internet.  
Le mot de passe enregistré est chiffré en base de données et la variable $_SESSION est initialisé pour garder ses information de connexion.

```php

/**
 * Connecte l'utilisateur
 *
 * @param $email : email de l'utilisateur
 * @param $password : mdp de l'utilisateur
 *
 * @return bool : true si la connexion à réussie
 */
function loginUser($email, $password): bool
{
    $res = false;
    // Connection data
    $databaseConnection = getDatabaseConnection();

    // Création statement
    $statement = $databaseConnection->prepare('
			SELECT
				password_uti
			FROM
				utilisateur
			WHERE
				email_uti = :email
		');

    // execute sql with actual values
    $statement->execute(array(
        'email' => trim($email)
    ));

    $passwordHash = $statement->fetch();

    if ($passwordHash) $res = password_verify($password, $passwordHash['password_uti']);

    if ($res) {
        $infoUser = getUserWithEmailAddress($email);
        createSession($infoUser);
    }

    return $res;
}
```

### Articles

l'utilisateur à la possibilité d'ajouter des commentaires aux articles écrit.

#### Affichage du détaille de l'article
L'application utilise Ajax pour créer des popup intéractive avec l'utilisateur et affciher en détaille un article et ses commentaires.

```php
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
    }```