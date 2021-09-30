<?php

require_once'variable.php';

function dump($t) {
    print_r('<pre>');
    var_dump($t);
    print_r('</pre>');
}

function getDatabaseConnection() {
    try { // connect to database and return connections
        $conn = new PDO('pgsql:host=localhost;dbname=footBall;password=admin;user=adminFoot;port=5432');
        return $conn;
    } catch (PDOException $e) {
        print "Erreur connection ! : " . $e->getMessage() . "<br/>";
        die();
    }
}

function getDataFromDataBase($tableName) {
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

function signUser($info) {
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
        'prenom_uti' => trim($info['Prenom']),
        'sexe_uti' => trim($info['sexe']),
        'email_uti' => trim($info['email']),
        'password_uti' => trim($info['password'])
    ));

    // return id of inserted row
    return $databaseConnection->lastInsertId();
}

function subscribeClub($id_uti, $clubs) {
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

function updateAvatar($id_uti, $infoFile) {
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

?>