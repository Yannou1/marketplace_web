
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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="script.js"></script>
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
              <li><a href="categories.php" >All categories</a></li>
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
  <div id="page-container">
    <div class="container">
      <div class="scrolling-text">
        <span class="message flash-sale">Vente flash</span>
        <span class="message promo-code">CODE PROMO : SOLDE</span>
      </div>

      <div id="item-detail">
        <div class="product-details">
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

          // Récupérer l'ID du produit à partir du paramètre d'URL
          $item_id = $_GET['itemId'];

          // Exécuter la requête SELECT pour récupérer les informations du produit
          $sql = "SELECT * FROM Item WHERE item_id = $item_id";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
              // Récupérer les informations du produit
              $row = $result->fetch_assoc();
              $product_details = array(
    'name' => $row['name'],
    'price' => $row['price'],
    'description' => $row['description'],
    'photo' => base64_encode($row['photo']), // Conversion en base64
    'stock' => $row['stock']
);

// Afficher les informations du produit
echo '<img src="data:image/jpeg;base64,' . $product_details['photo'] . '" alt="Product Image">';

              echo '<h2>' . $product_details['name'] . '</h2>';
    echo '<p>' . $product_details['description'] . '</p>';
    echo '<p>Price: $' . $product_details['price'] . '</p>';
    echo '<p>Stock: ' . $product_details['stock'] . '</p>';

    // Ajouter le formulaire pour sélectionner la quantité
    echo '<form action="process_cart.php" method="POST">';
echo '<input type="hidden" name="item_id" value="' . $item_id . '">';
echo '<label for="quantity">Quantity:</label>';
echo '<input type="number" name="quantity" id="quantity" min="1" max="' . $product_details['stock'] . '" value="1" required>';
echo '<button type="submit" name="buy">Buy</button>';
echo '</form>';

          } else {
              echo 'Produit non trouvé.';
          }

          $conn->close();
          ?>
        </div>
      </div>

    </div>
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
  </div>
</body>
</html>
