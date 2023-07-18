<?php
$username = "root";
$password = "root";
$database = "infinitydb";
$connection = new mysqli('localhost', $username, $password, $database);

// Vérifier la connexion
if ($connection->connect_error) {
    die("Erreur de connexion à la base de données : " . $connection->connect_error);
}

?>
