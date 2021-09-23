<?php

function print_r2($t)
{
    print_r('<pre>');
    print_r($t);
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

function getDataFromDataBase($tableName) 
{
    // get database connection
    $databaseConnection = getDatabaseConnection();
    // create our sql statment
    $statement = $databaseConnection->prepare('SELECT *
                                                FROM '.$tableName);

    // execute sql with actual values
    $statement->execute();

    // get and return user
    $data = $statement->fetchAll();
    return $data;
}







?>