<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Le reste de votre code pour interagir avec la base de données et afficher les données dans la page web
$nom = $_POST['nom'];
$email = $_POST['email'];

// Effectuer les opérations sur la base de données
// ...

// Exemple : Insérer les données dans une table
$sql = "INSERT INTO user (username, email) VALUES ('$nom', '$email')";

if ($conn->query($sql) === true) {
    echo "Enregistrement réussi";
} else {
    echo "Erreur lors de l'enregistrement : " . $conn->error;
}

// Fermer la connexion à la base de données
$conn->close();
?>
$conn->close();
?>
