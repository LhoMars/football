<?php

function print_r2($t)
{
    print_r('<pre>');
    print_r($t);
    print_r('</pre>');
}

function getDatabaseConnection() {
    try { // connect to database and return connections
        $conn = new PDO('pgsql:host=localhost;dbname=footBall;password=admin;user=adminFoot;port=5432');
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
    }
}
?>