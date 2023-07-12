<?php
include 'session.php';
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "infinitydb";

$connection = new mysqli($servername, $username, $password, $dbname);
if ($connection->connect_error) {
    die("Connexion échouée : " . $connection->connect_error);
}

$itemId = $_POST['item_id'];
$userId = $_SESSION['user_id'];
$price = $_POST['offer'];

// Insérer les détails de l'offre directe dans la table des offres
$query = "INSERT INTO direct_offers (item_id, user_id, price) VALUES ($itemId, $userId, $price)";
$result = mysqli_query($connection, $query);

if ($result) {
    // Succès de l'insertion, rediriger l'utilisateur vers la page de chat avec le vendeur
    header("Location: seller_chat.php?item_id=$itemId");
    exit;
} else {
    // Gestion de l'erreur lors de l'insertion
    echo "Une erreur s'est produite lors de la soumission de l'offre directe.";
}
?>
