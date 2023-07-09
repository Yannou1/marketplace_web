<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
  // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
  header("Location: login.php");
  exit();
}

// Vérifier si l'ID de l'article est fourni dans l'URL
if (isset($_GET['item_id'])) {
  $itemId = $_GET['item_id'];

  // Établir la connexion à la base de données
  $host = 'localhost'; // Remplacez par l'adresse de votre serveur de base de données
  $username = 'root'; // Remplacez par votre nom d'utilisateur de base de données
  $password = 'root'; // Remplacez par votre mot de passe de base de données
  $database = 'infinitydb'; // Remplacez par le nom de votre base de données

  $connection = mysqli_connect($host, $username, $password, $database);

  if (!$connection) {
    die('Erreur de connexion à la base de données : ' . mysqli_connect_error());
  }

  // Supprimer l'article du panier dans la table cart
  $deleteSql = "DELETE FROM cart WHERE item_id = $itemId AND user_id = {$_SESSION['user_id']} AND order_id IS NULL";
  $result = mysqli_query($connection, $deleteSql);

  if (!$result) {
    // Gérer l'erreur si la suppression échoue
    echo 'Erreur lors de la suppression de l\'article du panier : ' . mysqli_error($connection);
    exit;
  }

  // Fermer la connexion à la base de données
  mysqli_close($connection);

  // Rediriger vers la page du panier après la suppression
  header("Location: cart.php");
  exit;
} else {
  // Rediriger vers la page du panier si l'ID de l'article n'est pas fourni
  header("Location: cart.php");
  exit;
}
?>
