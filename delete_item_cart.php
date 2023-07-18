<?php
include 'session.php';
include 'db_connect.php';

// Vérifier si l'ID de l'article est fourni dans l'URL
if (isset($_GET['item_id'])) {
  $itemId = $_GET['item_id'];

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
