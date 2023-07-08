<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
  // Rediriger vers la page de connexion ou afficher un message d'erreur
  header("Location: account.php");
  exit;
}

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Récupérer les valeurs du formulaire
  $address = $_POST['address'];
  $paymentMethod = $_POST['payment_method'];

  // Établir la connexion à la base de données
  $host = 'localhost';
  $username = 'root';
  $password = 'root';
  $database = 'infinitydb';

  $connection = mysqli_connect($host, $username, $password, $database);

  if (!$connection) {
    die('Erreur de connexion à la base de données : ' . mysqli_connect_error());
  }

// Mettre à jour les commandes de l'utilisateur dans la base de données
$userId = $_SESSION['user_id'];
$sqlUpdate = "UPDATE orders SET status = 'f' WHERE user_id = $userId AND status = 'l'";
mysqli_query($connection, $sqlUpdate);

// Mettre à jour le stock des articles dans la table "Item" en fonction des commandes de l'utilisateur
$sqlUpdateStock = "UPDATE Item
JOIN orders ON Item.item_id = orders.item_id
SET Item.stock = Item.stock - orders.quantity
WHERE orders.status = 'f';

";

mysqli_query($connection, $sqlUpdateStock);



// Fermer la connexion à la base de données
mysqli_close($connection);

// Rediriger vers une page de confirmation de commande ou afficher un message de succès
header("Location: order_confirmation.php?success=true");
exit;


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="styles.css">
  <link rel="icon" href="images/test.jpeg" type="image/x-icon">

  <title>INFINITY - Checkout</title>
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
    <h2>Checkout</h2>
    <div class="checkout-form">
      <form method="POST" action="">
        <label for="address">Adresse de livraison:</label>
        <input type="text" id="address" name="address" required>
        <label for="payment-method">Moyen de paiement:</label>
        <input type="text" id="payment-method" name="payment_method" required>
        <button type="submit">Valider la commande</button>
      </form>
    </div>
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
