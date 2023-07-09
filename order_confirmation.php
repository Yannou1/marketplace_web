<?php
$success = isset($_GET['success']) && $_GET['success'] === 'true';

session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
  // Rediriger vers la page de connexion ou afficher un message d'erreur
  header("Location: account.php");
  exit;
}

// Établir la connexion à la base de données
$host = 'localhost';
$username = 'root';
$password = 'root';
$database = 'infinitydb';

$connection = mysqli_connect($host, $username, $password, $database);

if (!$connection) {
  die('Erreur de connexion à la base de données : ' . mysqli_connect_error());
}

// Récupérer l'ID de la commande à partir de l'URL
$orderId = $_GET['order_id'];

// Fermer la connexion à la base de données
mysqli_close($connection);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="styles.css">
  <link rel="icon" href="images/test.jpeg" type="image/x-icon">

  <title>INFINITY</title>
</head>
<body>
  <header>
    <div class="nav-category">
  <a href="#">
    <img src="logo/category.png" alt="Category">
    <span><b>Menu</b></span>
  </a>
    <div class="dropdown-menu">
      <ul>
        <li class="menu-item">
          <a href="categories.php">Categories</a>
          <ul class="sub-menu">
            <li><a href="categories.php">All categories</a></li>
            <li><a href="#">Apple product</a></li>
            <li><a href="#">Cars</a></li>
            <li><a href="#">Moto</a></li>
          </ul>
        </li>
        <li class="menu-item buy-menu-item">
          <a href="buy.php">Buy</a>
          <ul class="sub-menu">
            <li><a href="#">All</a></li>
            <li><a href="#">Buy it now</a></li>
            <li><a href="#">Auction</a></li>
            <li><a href="#">Best offers</a></li>
          </ul>
        </li>
        <li class="menu-item"><a href="sell.php">Sell</a></li>
        <!-- Ajoutez autant de choix que nécessaire -->
      </ul>
    </div>
</div>

    <div class="navigation">
      <a href="buy.php"><img src="logo/buying.png" alt="Buying"><span>Buy</span></a>
      <a href="sell.php"><img src="logo/sell.png" alt="Sell"><span>Sell</span></a>
    </div>
    <div class="logo-site">
      <a href="index.php"><img class="site-logo" src="logo/logo2.png" alt="Logo"></a>
    </div>

    <div class="nav-user">
      <a href="cart.php">
        
        <div class="user-link">
          <img src="logo/cart.png" alt="Cart">
          <span><b>Cart</b></span>
        </div>
      </a>
      <?php
      // Vérifier si l'utilisateur est connecté
      if (isset($_SESSION['username'])) {
        echo '<a href="profile.php">';
        echo '<div class="user-link">';
        echo '<img src="logo/account.png" alt="Account">';
        echo '<span><b>Profile</b></span>';
        echo '</div>';
        echo '</a>';
      } else {
        echo '<a href="account.php">';
        echo '<div class="user-link">';
        echo '<img src="logo/account.png" alt="Account">';
        echo '<span><b>Account</b></span>';
        echo '</div>';
        echo '</a>';
      }
      ?>
    </div>
  </header>
  <div class="container">
    <?php if ($success) : ?>
      <h2>Commande validée avec succès !</h2>
      <p>Merci pour votre commande.</p>
    <?php else : ?>
      <h2>Erreur lors de la validation de la commande</h2>
      <p>Il y a eu un problème lors de la validation de votre commande. Veuillez réessayer.</p>
    <?php endif; ?>
  </div>
  <footer>
  <div class="footer-container">
    <div class="footer-section">
      <h4>About</h4>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquam nunc ac est condimentum eleifend.</p>
    </div>
    <div class="footer-section">
      <h4>Contact</h4>
      <p>Email: contact@example.com</p>
    </div>
    <div class="footer-section">
      <h4>Useful links</h4>
      <ul>
        <li><a href="index.php">Accueil</a></li>
        <li><a href="categories.php">Categories</a></li>
        <li><a href="buy.php">Buy</a></li>
        <li><a href="sell.php">Sell</a></li>
        <li><a href="account.php">Account</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <p>All rights reserved &copy; 2023 - INFINITY Store</p>
  </div>
</footer>
  <script src="script.js"></script>
</body>
</html>
