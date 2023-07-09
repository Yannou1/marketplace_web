<?php
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

// Récupérer l'ID de l'utilisateur connecté
$userId = $_SESSION['user_id'];

// Récupérer les informations de l'utilisateur depuis la base de données
$sql = "SELECT first_name, last_name FROM user WHERE user_id = $userId";
$result = mysqli_query($connection, $sql);

if ($result && mysqli_num_rows($result) > 0) {
  $row = mysqli_fetch_assoc($result);
  $firstName = $row['first_name'];
  $lastName = $row['last_name'];
} else {
  // Gérer l'erreur si les informations de l'utilisateur ne peuvent pas être récupérées
  $firstName = '';
  $lastName = '';
}

// Vérifier si le formulaire de livraison est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delivery_form'])) {
  // Récupérer les valeurs du formulaire de livraison
  $shippingAddress = $_POST['street_address'] . ', ' . $_POST['city'] . ', ' . $_POST['postal_code'] . ', ' . $_POST['country'];

  // Enregistrer les informations de livraison dans la base de données
  $deliverySql = "INSERT INTO `orders` (user_id, shipping_address) VALUES ($userId, '$shippingAddress')";
  mysqli_query($connection, $deliverySql);

  // Récupérer l'ID de la commande créée
  $orderId = mysqli_insert_id($connection);

  // Mettre à jour la table cart avec l'order_id
  $updateCartSql = "UPDATE cart SET order_id = $orderId WHERE order_id IS NULL AND user_id = $userId";
  mysqli_query($connection, $updateCartSql);

  // Rediriger vers le formulaire de paiement avec l'ID de commande
  header("Location: checkout.php?step=payment&order_id=$orderId");
  exit;
}

// Vérifier si le formulaire de paiement est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_form'])) {
  // Récupérer les valeurs du formulaire de paiement
  $paymentType = $_POST['payment_type'];
  $cardNumber = $_POST['card_number'];
  $cardName = $_POST['card_name'];
  $cardExpiration = $_POST['card_expiration'];
  $cardSecurityCode = $_POST['card_security_code'];
  $totalPrice = $_POST['total_price']; // Récupérer la valeur du total price

  $paymentInformations = "Payment Type: $paymentType, Card Number: $cardNumber, Card Name: $cardName, Card Expiration: $cardExpiration, Card Security Code: $cardSecurityCode";

  // Récupérer l'ID de la commande depuis l'URL
  $orderId = $_GET['order_id'];

  // Mettre à jour la commande avec les informations de paiement et le total price
  $paymentSql = "UPDATE `orders` SET payment_informations = '$paymentInformations', amount = $totalPrice WHERE order_id = $orderId";
  mysqli_query($connection, $paymentSql);

  // Mettre à jour la commande avec la date d'achat
  $purchaseDate = date('Y-m-d');
  $purchaseDateSql = "UPDATE `orders` SET purchase_date = '$purchaseDate' WHERE order_id = $orderId";
  mysqli_query($connection, $purchaseDateSql);

  // Rediriger vers la page de confirmation de commande
  header("Location: order_confirmation.php?success=true");
  exit;
}

// Fermer la connexion à la base de données
mysqli_close($connection);
?>




<!-- Code HTML du fichier checkout.php -->

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
  <div class="containerA">
    <h2>Checkout</h2>
    <?php if (isset($_GET['step']) && $_GET['step'] === 'payment') : ?>
      <div class="checkout-form">
        <h3>Payment Information</h3>
        <form method="POST" action="">
          <input type="hidden" name="payment_form" value="true">
          <input type="hidden" name="order_id" value="<?php echo $_GET['order_id']; ?>">
          <?php if (isset($totalPrice)) : ?>
            <input type="hidden" name="total_price" value="<?php echo $totalPrice; ?>">
          <?php endif; ?>
          <label for="payment-type">Payment Type:</label>
          <select id="payment-type" name="payment_type" required>
            <option value="Visa">Visa</option>
            <option value="MasterCard">MasterCard</option>
            <option value="American Express">American Express</option>
            <option value="PayPal">PayPal</option>
          </select>
          <label for="card-number">Card Number:</label>
          <input type="text" id="card-number" name="card_number" required>
          <label for="card-name">Card Name:</label>
          <input type="text" id="card-name" name="card_name" required>
          <label for="card-expiration">Card Expiration:</label>
          <input type="text" id="card-expiration" name="card_expiration" required>
          <label for="card-security-code">Card Security Code:</label>
          <input type="text" id="card-security-code" name="card_security_code" required>
          <button type="submit">Submit Order</button>
        </form>
      </div>
    <?php else : ?>
      <div class="checkout-form">
        <h3>Delivery Information</h3>
        <form method="POST" action="">
          <input type="hidden" name="delivery_form" value="true">
          <label for="first-name">First Name:</label>
    <input type="text" id="first-name" name="first_name" value="<?php echo isset($firstName) ? $firstName : ''; ?>" required>
    <label for="last-name">Last Name:</label>
    <input type="text" id="last-name" name="last_name" value="<?php echo isset($lastName) ? $lastName : ''; ?>" required>
          <label for="street-address">Street Address:</label>
          <input type="text" id="street-address" name="street_address" required>
          <label for="city">City:</label>
          <input type="text" id="city" name="city" required>
          <label for="postal-code">Postal Code:</label>
          <input type="text" id="postal-code" name="postal_code" required>
          <label for="country">Country:</label>
          <input type="text" id="country" name="country" required>
          <label for="phone-number">Phone Number:</label>
          <input type="text" id="phone-number" name="phone_number" required>
          <button type="submit">Next Step</button>
        </form>
      </div>
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
