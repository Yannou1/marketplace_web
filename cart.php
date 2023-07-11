<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
  // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
  header("Location: login.php");
  exit();
}

// Vérifier si l'ID de l'article est fourni dans l'URL et si la méthode de requête est "GET"
if (isset($_GET['item_id']) && $_SERVER['REQUEST_METHOD'] === 'GET') {
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
}
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
    <div class="scrolling-text">
      <span class="message flash-sale">Vente flash</span>
      <span class="message promo-code">CODE PROMO : SOLDE</span>
    </div>
    <div class="cart">
      <h2>Cart</h2>
      <div id="cart-items">
        <?php
        // Établir la connexion à la base de données
        $host = 'localhost'; // Remplacez par l'adresse de votre serveur de base de données
        $username = 'root'; // Remplacez par votre nom d'utilisateur de base de données
        $password = 'root'; // Remplacez par votre mot de passe de base de données
        $database = 'infinitydb'; // Remplacez par le nom de votre base de données

        $connection = mysqli_connect($host, $username, $password, $database);

        if (!$connection) {
            die('Erreur de connexion à la base de données : ' . mysqli_connect_error());
        }

        // Récupérer les articles dans le panier de l'utilisateur connecté
$userId = $_SESSION['user_id'];
$sql = "SELECT Item.*, cart.quantity FROM Item INNER JOIN cart ON Item.item_id = cart.item_id WHERE cart.user_id = $userId AND cart.order_id IS NULL";
$result = mysqli_query($connection, $sql);

// Vérifier s'il y a des résultats
if (mysqli_num_rows($result) > 0) {
    // Variable pour stocker le total des prix des articles
    $totalPrice = 0;
    // Variable pour stocker le total de la quantité des articles
    $totalQuantity = 0;

    while ($row = mysqli_fetch_assoc($result)) {
        $itemId = $row['item_id'];
        $itemName = $row['name'];
        $itemPrice = $row['price'];
        $itemQuantity = $row['quantity'];
        $itemPhoto = $row['photo'];
        $itemPhotoEncoded = base64_encode($itemPhoto);

        echo '<div class="cart-item-row">';
        echo '<div class="cart-item-details">';
        echo '<img src="data:image/jpeg;base64,' . $itemPhotoEncoded . '" alt="' . $itemName . '" class="small-photo">';
        echo '<p>' . $itemName . '</p>';
        echo '<p>$' . $itemPrice . '</p>';
        echo '<p>' . $itemQuantity . '</p>';
        echo '</div>';
        echo '<a href="delete_item_cart.php?item_id=' . $itemId . '"><img src="logo/trash.png"></a>';
        echo '</div>';

        // Calculer le prix total de cet article
        $itemTotalPrice = $itemPrice * $itemQuantity;
        // Ajouter le prix total de cet article au total des prix
        $totalPrice += $itemTotalPrice;
        // Ajouter la quantité de cet article au total de la quantité
        $totalQuantity += $itemQuantity;
    }

    echo '<div class="cart-summary">';
    echo '<div class="cart-summary-details">';
    echo '<div class="cart-total">';
    echo '<span class="total-label">Total Quantity:</span>';
    echo '<span class="total-quantity">' . $totalQuantity . '</span>';
    echo '</div>';
    echo '<div class="cart-total">';
    echo '<span class="total-label">Total Price:</span>';
    echo '<span class="total-quantity">$' . number_format($totalPrice, 2) . '</span>';
    echo '</div>';

    // Vérifier s'il y a des articles dans le panier
    if ($totalQuantity > 0) {
        echo '<div class="checkout-button">';
        echo '<button onclick="location.href=\'checkout.php\'">Passer la commande</button>';
        echo '</div>';
    } else {
        echo '<div class="empty-cart-message">Votre panier est vide.</div>';
    }

    echo '</div>';
    echo '</div>';
} else {
    echo '<p>No items found.</p>';
}

// Fermer la connexion à la base de données
mysqli_close($connection);
        ?>
      </div>
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