<?php

include 'session.php';
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "infinitydb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connexion échouée : " . $conn->connect_error);
}

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
  // Récupérer l'ID de l'utilisateur à partir de la session
  $user_id = $_SESSION['user_id'];
  echo "ID de l'utilisateur : " . $user_id;

  // Vérifier si le formulaire a été soumis
  if (isset($_POST['submit'])) {
    // Récupérer les données du formulaire
    $category_name = $_POST['category'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $saleType = $_POST['sale_type'];
    $stock = $_POST['stock'];

    // Récupérer le fichier de la photo
    $photo = $_FILES['photo'];

    // Vérifier si une photo a été téléchargée
    if (!empty($photo['tmp_name'])) {
      // Lire le contenu de l'image
      $photoContent = file_get_contents($photo['tmp_name']);

      // S'échapper pour l'insertion dans la requête SQL
      $escapedPhotoContent = $conn->real_escape_string($photoContent);

      // Récupérer l'ID de catégorie correspondant au nom de catégorie
      $sql_category = "SELECT category_id FROM category WHERE name = '$category_name'";
      $result_category = $conn->query($sql_category);

      if ($result_category->num_rows > 0) {
        $row_category = $result_category->fetch_assoc();
        $category_id = $row_category['category_id'];
      } else {
        // La catégorie n'existe pas ou une erreur s'est produite
        echo "Erreur : Catégorie non trouvée.";
        exit;
      }

      // Préparer la requête SQL pour insérer l'article avec la photo
      $sql = "INSERT INTO Item (user_id, category_id, name, description, price, sale_type, photo, stock) VALUES ('$user_id', '$category_id', '$name', '$description', '$price', '$saleType', '$escapedPhotoContent','$stock')";

      // Exécuter la requête SQL
      if ($conn->query($sql) === TRUE) {
        // L'article a été ajouté avec succès
        echo "L'article a été ajouté avec succès.";
      } else {
        // Une erreur s'est produite lors de l'ajout de l'article
        echo "Erreur : Impossible d'ajouter l'article." . $conn->error;
      }
    } else {
      // La photo n'a pas été téléchargée
      echo "Erreur : Veuillez sélectionner une photo.";
    }
  }
} 

// Fermer la connexion à la base de données
$conn->close();
// Rediriger vers la page cart.php
header("Location: sell.php");
exit;
?>
