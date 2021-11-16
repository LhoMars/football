<?php

require_once 'variable.php';

/**
 * Affiche un text en format balise pre
 *
 * @param $t : le text à afficher
 */
function dump($t): void
{
    print_r('<pre>');
    var_dump($t);
    print_r('</pre>');
}

/**
 * Récupère la connexion de la bdd
 *
 * @return PDO|null : objet PDO de bdd ou rien si la connexion à échoué
 */
function getDatabaseConnection(): PDO|null
{
    try { // connect to database and return connections
        return new PDO('pgsql:host=localhost;dbname=footBall;password=admin;user=adminFoot;port=5432');
    } catch (PDOException $e) {
        print "Erreur connection ! : " . $e->getMessage() . "<br/>";
        die();
    }
}

/**
 * Récupère les données d'une table
 *
 * @param $tableName : nom de la table
 *
 * @return bool|array : false si la table n'existe pas ou le tableau des données
 */
function getDataFromDataBase($tableName): bool|array
{
    // get database connection
    $databaseConnection = getDatabaseConnection();
    // create our sql statment
    $statement = $databaseConnection->prepare('SELECT *
                                                FROM ' . $tableName);

    // execute sql with actual values
    $statement->execute();

    // get and return user
    return $statement->fetchAll();
}

/**
 *  Enregistre un utilisateur en bdd
 *
 * @param $info : toute les information du formulaire d'enregistrement
 *
 * @return int : id utilisateur
 */
function signUser($info): int
{
    // get database connection
    $databaseConnection = getDatabaseConnection();

    // create our sql statment
    $statement = $databaseConnection->prepare('
			INSERT INTO
				UTILISATEUR (
                    id_uti,
					id_club,
					nom_uti,
					prenom_uti,
					sexe_uti,
					email_uti,
					password_uti,
                                        date_inscription
				)
			VALUES (
                default,
				:id_club,
				:nom_uti,
				:prenom_uti,
				:sexe_uti,
				:email_uti,
				:password_uti,
                                now()
			)
		');

    // execute sql with actual values
    $statement->execute(array(
        'id_club' => trim($info['clubFavori']),
        'nom_uti' => trim(strip_tags($info['nom'])),
        'prenom_uti' => trim(strip_tags($info['prenom'])),
        'sexe_uti' => trim($info['sexe']),
        'email_uti' => trim(strip_tags($info['email'])),
        'password_uti' => trim($info['password'])
    ));

    // return id of inserted row
    return $databaseConnection->lastInsertId();
}

/**
 *  Abonne un utilisateur à une liste de club
 *
 * @param $id_uti : id de l'utilsiateur
 * @param $clubs : tableau de nom de club
 */
function subscribeClub($id_uti, $clubs): void
{
    $databaseConnection = getDatabaseConnection();
    foreach ($clubs as $club) {

//        dump($club);die;
        // create our sql statment
        $statement = $databaseConnection->prepare('
			INSERT INTO
				S_ABONNER (
					id_uti,
					id_club
				)
			VALUES (
				:id_uti,
				:id_club
			)
		');

        // execute sql with actual values
//        dump($id_uti);
//        dump($club);die;
        $statement->execute(array(
            'id_uti' => trim($id_uti),
            'id_club' => trim($club)
        ));
    }
}

/**
 * Modifie l'avatar de l'utilisateur
 *
 * @param $id_uti : id de lutilisateur
 * @param $infoFile : information du fichier avec $_FILES
 *
 */
function updateAvatar($id_uti, $infoFile)
{
    dump($infoFile);
    if (exif_imagetype($infoFile['tmp_name'])) {
        dump("1");
        if ($infoFile['size'] <= 4194304) {


            $ext = pathinfo($infoFile['name'], PATHINFO_EXTENSION);
//          dump($ext);

            $nomFichier = "{$id_uti}.{$ext}";
//          dump($nomFichier);

            // Chemin absolu de l'avatar de l'utilisateur
            $uploadfile = ROOT_PATH . "asset/avatarUtilisateur/{$nomFichier}";

            // Déplace le fichier de xamp/temp à l'endroit choisi
            if (move_uploaded_file($infoFile['tmp_name'], $uploadfile)) {

                $databaseConnection = getDatabaseConnection();

                $statement = $databaseConnection->prepare('
			update utilisateur
                        set image_uti = :nomFile
                        where id_uti = :id_uti;
		');

                $statement->execute(array(
                    'id_uti' => $id_uti,
                    'nomFile' => $nomFichier
                ));
            }
        dump('ok');
        }
    }
}

/**
 * Met à jour les information d'un utilisateur
 *
 * @param $id_uti : id de l'utilisateur
 * @param $tab : toutes les valeurs à changer avec key->value
 */
function updateUser($id_uti, $tab): void
{
    $databaseConnection = getDatabaseConnection();

    $query = ' UPDATE
                    utilisateur
		SET 
                ';
    $params = array();
    foreach ($tab as $key => $value) {
        if ($value != '') {
            $colum = '';

            switch ($key) {
                case 'nom':
                    $colum = 'nom_uti';
                    break;

                case 'prenom':
                    $colum = 'prenom_uti';
                    break;

                case 'sexe':
                    $colum = 'sexe_uti';
                    break;

                case 'email':
                    $colum = 'email_uti';
                    break;

                case 'password':
                    $colum = 'password_uti';
                    break;

                case 'clubFavori':
                    $colum = 'id_club';
                    break;

                default:
                    $colum = '';
                    break;
            }

            if ($colum !== "") {
                $query .= " {$colum} = :{$colum},";
                $params += [$colum => strip_tags($value)];
            }
        }
    }

    $query = rtrim($query, ',');
    $query .= ' WHERE
		id_uti = :id_uti';

    $params += ['id_uti' => trim($id_uti)];
    $statement = $databaseConnection->prepare($query);

    dump($query);
    dump($params);
    die;

    $statement->execute($params);
}

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

/**
 * Créer une session pour un utilisateur
 *
 * @param $info : les information de l'utilisateur
 */
function createSession($info): void
{
    $_SESSION['user_info'] = $info;
    $_SESSION['is_logged_in'] = true;
}

/**
 * Récupère une ligne d'une table avec l'id
 *
 * @param string $tableName
 * @param string $column
 * @param string $value
 *
 * @return array $info
 */
function getRowWithValue(string $tableName, string $column, string $value): array
{
    // get database connection
    $databaseConnection = getDatabaseConnection();

    // create our sql statment
    $statement = $databaseConnection->prepare('
			SELECT
				*
			FROM
				' . $tableName . '
			WHERE
				' . $column . ' = :' . $column
    );

    // execute sql with actual values
    $statement->setFetchMode(PDO::FETCH_ASSOC);
    $statement->execute(array(
        $column => trim($value)
    ));

    // get and return user
    $user = $statement->fetch();
    return $user;
}

/**
 * Récupère l'utilisateur avec son email
 *
 * @param string $email
 *
 * @return array|bool $userInfo : information de l'utilisateur ou false si l'utilisateur n'existe pas
 */
function getUserWithEmailAddress(string $email): array|bool
{
    // get database connection
    $databaseConnection = getDatabaseConnection();

    // create our sql statment
    $statement = $databaseConnection->prepare('
			SELECT
				*
			FROM
				utilisateur
			WHERE
				email_uti = :email
		');

    // execute sql with actual values
    $statement->setFetchMode(PDO::FETCH_ASSOC);
    $statement->execute(array(
        'email' => trim(strip_tags($email))
    ));

    // get and return user
    return $statement->fetch();
}

/**
 * Vérifie que l'utilisateur est connecté
 *
 * @param void
 *
 * @return bool
 */
function isLoggedIn(): bool
{
    if ((isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in']) && (isset($_SESSION['user_info']) && $_SESSION['user_info'])) { // check session variables, user is logged in
        return true;
    } else { // user is not logged in
        return false;
    }
}

/**
 * Déconnect un utilisateur
 */
function loGoout(): void
{
    session_destroy();
}

/**
 * Redirige si l'utilisateur est connectée
 */
function loggedInRedirect(): void
{
    if (isLoggedIn()) { // user is logged in
        // send them to the home page
        header('location:' . INCLUDE_DIR . 'index.php');
    }
}

/**
 * Enregistre un log d'un utilisateur
 *
 * @param int $id_uti : id de l'utilisateur
 * @param string $ip : adresse ip utilisateur
 * @param bool $connValue : etat de la connection
 */
function saveLog(int $id_uti, string $ip, bool $connValue): void
{
    $value = $connValue ? 'true' : 'false';

    // get database connection
    $databaseConnection = getDatabaseConnection();

    // create our sql statment
    $statement = $databaseConnection->prepare('
			INSERT INTO
				LOGS (
				    id,
                    id_uti,
					date_log,
					ip_log,
					status
				)
			VALUES (
                default,
			    :id_uti,
				now(),
				:ip_log,
				:status                                
			)
		');

    // execute sql with actual values
    $statement->execute(array(
        'id_uti' => trim($id_uti),
        'ip_log' => trim($ip),
        'status' => trim($value)
    ));

}

/**
 * Fonction pour obtenir l'adresse ip du client
 *
 * @return string : l'adresse ip ou UNKNOWN si inconnue
 */
function getIpClient(): string
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

/**
 * Vérifie si l'utilisateur ne force pas la connection
 * @param $id_uti : id de l'utilisateur
 *
 * @return bool : autorise ou non la connection de l'utilisateur
 */
function EnableConnectionUser($id_uti): bool
{
    $res = false;

    // get database connection
    $databaseConnection = getDatabaseConnection();

    // create our sql statment
    $statement = $databaseConnection->prepare("
			SELECT liste.status,min(liste.date_log) as dmin, 
			    max(liste.date_log) as dmax,
                max(liste.date_log)-min(liste.date_log) as diff,
                now()-min(liste.date_log) as plusAncien,
                now()-max(liste.date_log) as plusRecent


            FROM (
                SELECT * from logs l
                where l.id_uti = :id_uti
                order by l.date_log desc
                limit 10
                ) as liste
            group by liste.status
            having now()-min(liste.date_log) < '00:20:00';
		");

    // execute sql with actual values
    $statement->execute(array(
        'id_uti' => trim($id_uti)
    ));

    return $res;
}

/**
 * Enregistre un commentaire
 *
 * @param string $idArticle : id de l'article pour le commentaire
 * @param string $nom : nom de l'utilisateur
 * @param string $text : commentaire de l'utilisateur
 */
function addCommentaire(string $idArticle, string $nom, string $text): void
{
    $databaseConnection = getDatabaseConnection();

    // create our sql statment
    $statement = $databaseConnection->prepare('
			INSERT INTO
				COMMENTAIRE (
					id_commentaire,
					id_article,
				    nom_commentaire,
				    text_commentaire
				)
			VALUES (
			    default,
				:id_article,
			    :nom_commentaire,
                :text_commentaire
			)
		');

    // execute sql with actual values
    $statement->execute(array(
        'id_article' => trim($idArticle),
        'nom_commentaire' => trim(strip_tags($nom)),
        'text_commentaire' => trim(strip_tags($text))
    ));
}

/**
 * Créer un rendu html pour les commentaires
 *
 * @param $id_article : id de l'article
 *
 * @return string : contenu html à afficher
 */
function renderCommentaire($id_article): string
{
    $return = "";
    $databaseConnection = getDatabaseConnection();

    // create our sql statment
    $statement = $databaseConnection->prepare('
			SELECT * 
			FROM COMMENTAIRE
			WHERE
				id_article = :id_article
		');

    // execute sql with actual values
    $statement->execute(array(
        'id_article' => trim($id_article)
    ));
    $tab = $statement->fetchAll();


    ob_start();
    ?>
    <div class="contenu" style="max-height: 450px;">
        <h3>Commentaires</h3>
        <?php foreach ($tab as $commentaire) {
            ?>
            <div>
                <?php
                $nom = $commentaire['nom_commentaire'];
                $text = $commentaire['text_commentaire'];
                ?>
                <p><b><?= $nom; ?></b>
                    <?= " : " . $text; ?></p>
            </div>
            <?php
        }
        ?>
        </table>
    </div>
    <?php
    $return .= ob_get_contents();
    ob_end_clean();
    return $return;
}

/**
 * Donne le premier commentaire d'un article
 *
 * @param $id_article : id de l'article
 *
 * @return array|bool : le commentaire le plus récent ou false si l'utilisateur n'existe pas
 */
function getFirstCom($id_article): array|bool
{

    // get database connection
    $databaseConnection = getDatabaseConnection();

    // create our sql statment
    $statement = $databaseConnection->prepare("
			SELECT *
            FROM commentaire 
            WHERE id_commentaire = (
                SELECT max(id_commentaire)
                FROM commentaire
                WHERE id_article = :id_article
                )
		");

    // execute sql with actual values
    $statement->execute(array(
        'id_article' => trim($id_article)
    ));

    return $statement->fetch();;
}

function testConn($id, $pass)
{
    // Connection data
    $databaseConnection = getDatabaseConnection();

    // Création statement
    $res = $databaseConnection->query("
                select email_uti, password_uti 
                from utilisateur 
                where email_uti = '$id' 
                  and password_uti='$pass'");

    if ($res->rowCount() > 0) {
        return true;
    }
    return false;
}


?>