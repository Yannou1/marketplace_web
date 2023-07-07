<?php
// Démarrer la session
session_start();
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

        // Récupérer les commandes de l'utilisateur depuis la table orders
        $userId = $_SESSION['user_id'];// Remplacez par l'ID de l'utilisateur connecté (exemple : 1)
        $sql = "SELECT * FROM orders WHERE user_id = $userId";
        $result = mysqli_query($connection, $sql);

        // Vérifier s'il y a des résultats
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $orderId = $row['order_id'];
                $itemId = $row['item_id'];

                // Récupérer les détails de l'article depuis la table Item
                $sqlItem = "SELECT * FROM Item WHERE item_id = $itemId";
                $resultItem = mysqli_query($connection, $sqlItem);
                $rowItem = mysqli_fetch_assoc($resultItem);

                $itemName = $rowItem['name'];
                $itemPrice = $rowItem['price'];
                $quantity = $row['quantity'];

                echo '<div class="cart-item">';
echo '<p>' . $itemName . '</p>';
echo '<p>$' . $itemPrice . '</p>';
echo '<p>$' . $quantity . '</p>';
echo '<a href="delete_order.php?order_id=' . $orderId . '"><img src="logo/trash.png"</a>';
echo '</div>';

            }
        } else {
            echo '<p>No orders found.</p>';
        }

        // Calculer le total basé sur les prix des articles
        $sqlTotal = "SELECT SUM(price) AS total FROM Item WHERE item_id IN (SELECT item_id FROM orders WHERE user_id = $userId)";
        $resultTotal = mysqli_query($connection, $sqlTotal);
        $rowTotal = mysqli_fetch_assoc($resultTotal);
        $totalPrice = $rowTotal['total'];

        echo '<div class="cart-summary">';
        echo '<div>Total: $' . $totalPrice . '</div>';
        echo '</div>';
        echo '<div class="checkout-button">';
    echo '<button onclick="checkout()">Passer la commande</button>';
    echo '</div>';

        // Fermer la connexion à la base de données
        mysqli_close($connection);
        ?>
      </div>
    </div>
  </div>
  <script src="script.js"></script>
</body>
<footer>
  <div class="footer-container">
    <div class="footer-section">
      <h4>A propos</h4>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquam nunc ac est condimentum eleifend.</p>
    </div>
    <div class="footer-section">
      <h4>Contact</h4>
      <p>Email: contact@example.com</p>
      <p>Téléphone: 123-456-7890</p>
    </div>
    <div class="footer-section">
      <h4>Liens utiles</h4>
      <ul>
        <li><a href="#">Accueil</a></li>
        <li><a href="#">Acheter</a></li>
        <li><a href="#">Vendre</a></li>
        <li><a href="#">À propos</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <p>All rights reserved &copy; 2023 - INFINITY Store</p>
  </div>
</footer>
</html>
