<?php
// Inclure le fichier de connexion à la base de données
include 'db_connect.php';

// Démarrer la session
session_start();

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Récupérer les données du formulaire
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Rechercher l'utilisateur dans la base de données en utilisant une requête préparée
  $stmt = $connection->prepare("SELECT * FROM User WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    // Vérifier le mot de passe
    if ($password === $row['password']) {
      // Authentification réussie

      // Stocker les informations de l'utilisateur dans la session
      $_SESSION['user_id'] = $row['user_id'];
      $_SESSION['username'] = $row['username'];

      // Rediriger l'utilisateur vers une autre page (par exemple, la page d'accueil)
      header("Location: index.php");
      exit();
    } else {
      // Mot de passe incorrect
      $_SESSION['error_message'] = "Mot de passe incorrect.";
      header("Location: account.php");
      exit();
    }
  } else {
    // Utilisateur non trouvé
    $_SESSION['error_message'] = "Utilisateur non trouvé.";
    header("Location: account.php");
    exit();
  }

  // Fermer la connexion à la base de données
  $stmt->close();
  $connection->close();
}
?>
