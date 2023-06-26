<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['passwords'];

// Vérification des informations de connexion
$query = "SELECT * FROM user WHERE email = '$email' AND password = '$password'";
$result = $conn->query($query);

if ($result->num_rows == 1) {
    
    echo "Connexion réussie";
} else {
    echo "Identifiants invalides";
}

// Fermeture de la connexion à la base de données
$conn->close();
?>
