<?php
$username = "root";
$password = "root";
$database = "infinitydb";
$conn = new mysqli('localhost', $username, $password, $database, $port);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

echo '<p>Connexion réussie : '. $conn->host_info.'</p>';
?>
