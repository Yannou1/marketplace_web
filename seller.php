<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "infinitydb";

// Créer une connexion
$connection = mysqli_connect($servername, $username, $password, $dbname);

// Vérifier la connexion
if (!$connection) {
    die("Échec de la connexion à la base de données : " . mysqli_connect_error());
}

// Démarrer la session
session_start();

// Récupérer l'ID du vendeur à partir du paramètre d'URL
$sellerId = $_GET['seller_id'];

// Récupérer les informations du vendeur à partir de la table User
$query = "SELECT * FROM User WHERE user_id = $sellerId";
$result = mysqli_query($connection, $query);

if (!$result) {
    die('Erreur lors de la récupération des informations du vendeur : ' . mysqli_error($connection));
}

// Vérifier si le vendeur existe
if (mysqli_num_rows($result) == 0) {
    die('Vendeur non trouvé.');
}

// Récupérer les informations du vendeur
$seller = mysqli_fetch_assoc($result);

// Récupérer tous les produits (items) associés à cet utilisateur
$query = "SELECT * FROM Item WHERE user_id = $sellerId";
$result = mysqli_query($connection, $query);

if (!$result) {
    die('Erreur lors de la récupération des produits du vendeur : ' . mysqli_error($connection));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="icon" href="images/test.jpeg" type="image/x-icon">
    <title>INFINITY - Seller</title>
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
            <div class="seller">
            <?php
    // Récupérer la photo de profil encodée en base64
    $profilePhoto = base64_encode($seller['profile_photo']);
    ?>
    <img class="profile-photo" src="data:image/jpeg;base64,<?php echo $profilePhoto; ?>" alt="Profile Photo">

    
            <h2>Seller: <?php echo $seller['username']; ?></h2>
            <!-- Afficher d'autres informations sur le vendeur si nécessaire -->

            <h3>Produits du vendeur:</h3>


            <table class="seller-table">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Prix</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Afficher les informations de chaque produit
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<td><img src="data:image/jpeg;base64,' . base64_encode($row['photo']) . '" alt="Product Image"></td>';
                        echo '<td>' . $row['name'] . '</td>';
                        echo '<td>' . $row['description'] . '</td>';
                        echo '<td>$' . $row['price'] . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        </div>
    </div>
    <script src="script.js"></script>
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
</body>
</html>
