<?php
// Établir la connexion à la base de données (placez ce code en haut du fichier)
$host = 'localhost'; // Remplacez par l'adresse de votre serveur de base de données
$username = 'root'; // Remplacez par votre nom d'utilisateur de base de données
$password = 'root'; // Remplacez par votre mot de passe de base de données
$database = 'infinitydb'; // Remplacez par le nom de votre base de données

$connection = mysqli_connect($host, $username, $password, $database);

if (!$connection) {
    die('Erreur de connexion à la base de données : ' . mysqli_connect_error());
}

// Vérifier si l'ID de la commande est passé en tant que paramètre
if (isset($_GET['order_id'])) {
    $orderId = $_GET['order_id'];

    // Supprimer la commande de la base de données
    $sql = "DELETE FROM orders WHERE order_id = $orderId";
    $result = mysqli_query($connection, $sql);

    if ($result) {
        echo 'La commande a été supprimée avec succès.';
    } else {
        echo 'Erreur lors de la suppression de la commande : ' . mysqli_error($connection);
    }
} else {
    echo 'ID de commande non spécifié.';
}

// Fermer la connexion à la base de données
mysqli_close($connection);

// Rediriger vers la page cart.php
header("Location: cart.php");
exit;
?>
