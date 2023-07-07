<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "infinitydb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connexion échouée : " . $conn->connect_error);
}

// Vérifier si l'ID de l'item a été fourni
if (isset($_POST['item_id'])) {
  // Récupérer l'ID de l'item à supprimer
  $item_id = $_POST['item_id'];

  // Préparer la requête SQL pour supprimer l'item
  $sql = "DELETE FROM Item WHERE item_id = '$item_id'";

  // Exécuter la requête SQL
  if ($conn->query($sql) === TRUE) {
    // La suppression a réussi
    echo "L'item a été supprimé avec succès.";
  } else {
    // Une erreur s'est produite lors de la suppression de l'item
    echo "Erreur : Impossible de supprimer l'item." . $conn->error;
  }
}

// Fermer la connexion à la base de données
$conn->close();
?>
