<?php

require_once 'variable.php';

function dump($t)
{
    print_r('<pre>');
    var_dump($t);
    print_r('</pre>');
}

function getDatabaseConnection()
{
    try { // connect to database and return connections
        $conn = new PDO('pgsql:host=localhost;dbname=footBall;password=admin;user=adminFoot;port=5432');
        return $conn;
    } catch (PDOException $e) {
        print "Erreur connection ! : " . $e->getMessage() . "<br/>";
        die();
    }
}

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
    $data = $statement->fetchAll();
    return $data;
}

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
        'nom_uti' => trim($info['nom']),
        'prenom_uti' => trim($info['prenom']),
        'sexe_uti' => trim($info['sexe']),
        'email_uti' => trim($info['email']),
        'password_uti' => trim($info['password'])
    ));

    // return id of inserted row
    return $databaseConnection->lastInsertId();
}

function subscribeClub($id_uti, $clubs)
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
            'id_uti' => $id_uti,
            'id_club' => $club
        ));
    }
}

function updateAvatar($id_uti, $infoFile): string
{
    $databaseConnection = getDatabaseConnection();

    $ext = pathinfo($infoFile['name'], PATHINFO_EXTENSION);
//    dump($ext);

    $nomFichier = $id_uti . '.' . $ext;
//    dump($nomFichier);

    $statement = $databaseConnection->prepare('
			update utilisateur
                        set image_uti = :nomFile
                        where id_uti = :id_uti;
		');

    $statement->execute(array(
        'id_uti' => $id_uti,
        'nomFile' => $nomFichier
    ));

    return $nomFichier;
}

/**
 * Met à jour les information d'un utilisateur
 * @param $id_uti
 * @param $tab
 */
function updateUser($id_uti, $tab)
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
                $params += [$colum => $value];
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

function loginUser($email, $password) : bool
{
    $res =false;
    // Connection data
    $databaseConnection = getDatabaseConnection();

    // Création statement
    $statement = $databaseConnection->prepare( '
			SELECT
				password_uti
			FROM
				utilisateur
			WHERE
				email_uti = :email
		' );

    // execute sql with actual values
    $statement->execute( array(
        'email' => trim( $email )
    ) );

    $passwordHash = $statement->fetch();

    if($passwordHash)$res = password_verify($password, $passwordHash['password_uti']);

    if($res)
    {
    $infoUser = getUserWithEmailAddress($email);
    createSession($infoUser);
    }

    return $res;
}

function createSession($info)
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
function getRowWithValue(string $tableName, string $column, string $value ): array
{
    // get database connection
    $databaseConnection = getDatabaseConnection();

    // create our sql statment
    $statement = $databaseConnection->prepare( '
			SELECT
				*
			FROM
				' . $tableName . '
			WHERE
				' . $column . ' = :' . $column
    );

    // execute sql with actual values
    $statement->setFetchMode( PDO::FETCH_ASSOC );
    $statement->execute( array(
        $column => trim( $value )
    ) );

    // get and return user
    $user = $statement->fetch();
    return $user;
}

/**
 * Récupère l'utilisateur avec son email
 *
 * @param string $email
 *
 * @return array|bool $userInfo
 */
function getUserWithEmailAddress(string $email ): array|bool {
    // get database connection
    $databaseConnection = getDatabaseConnection();

    // create our sql statment
    $statement = $databaseConnection->prepare( '
			SELECT
				*
			FROM
				utilisateur
			WHERE
				email_uti = :email
		' );

    // execute sql with actual values
    $statement->setFetchMode( PDO::FETCH_ASSOC );
    $statement->execute( array(
        'email' => trim( $email )
    ) );

    // get and return user
    $user = $statement->fetch();
    return $user;
}

/**
 * Vérifie que l'utilisateur est connecté
 *
 * @param void
 *
 * @return boolean
 */
function isLoggedIn(): bool {
    if ( ( isset( $_SESSION['is_logged_in'] ) && $_SESSION['is_logged_in'] ) && ( isset( $_SESSION['user_info'] ) && $_SESSION['user_info'] ) ) { // check session variables, user is logged in
        return true;
    } else { // user is not logged in
        return false;
    }
}

function loGoout(){
    session_destroy();
}

/**
 * Si l'utilisateur est connecter on le redirige
 *
 * @param void
 *
 * @return boolean
 */
function loggedInRedirect()
{
    if ( isLoggedIn() ) { // user is logged in
        // send them to the home page
        header( 'location:'.INCLUDE_DIR.'index.php' );
    }
}

/**
 * @param int $id_uti : id de l'utilisateur
 * @param string $ip : adresse ip utilisateur
 * @param boolean $connValue : etat de la connection
 */
function saveLog(int $id_uti, string $ip, bool $connValue) {
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
 * Function to get the client IP address
 * @return array|false|string
 */
function getIpClient(): bool|array|string
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function EnableConnectionUser($id_uti): bool {
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
		") ;

    // execute sql with actual values
    $statement->execute(array(
        'id_uti' => trim($id_uti)
    ));

    return $res;
}

?>