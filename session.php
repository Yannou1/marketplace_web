<?php
// session.php

// Démarrer la session
session_start();

// Vérifier si l'utilisateur est authentifié
if (!isset($_SESSION['user_id'])) {
  // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
  header("Location: account.php");
  exit();
}
?>
