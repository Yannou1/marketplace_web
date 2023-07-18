<?php
include 'db_connect.php';

// Vérifier si l'ID de l'item a été fourni
if (isset($_POST['item_id'])) {
  // Récupérer l'ID de l'item à supprimer
  $item_id = $_POST['item_id'];

  // Préparer la requête SQL pour supprimer l'item
  $sql = "DELETE FROM Item WHERE item_id = '$item_id'";

  // Exécuter la requête SQL
  if ($connection->query($sql) === TRUE) {
    // La suppression a réussi
    echo "The item has been successfully deleted.";
  } else {
    // Une erreur s'est produite lors de la suppression de l'item
    echo "Error: Unable to delete item." . $connection->error;
  }
}

// Fermer la connexion à la base de données
$connection->close();
?>
