<?php
// Inclure le fichier session.php
include 'session.php';
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
    <div class="hero-banner">
      <div class="banner-container">
        <div class="banner-image active">
          <img src="images/ban.jpeg" alt="Offer 1">
        </div>
        <div class="banner-image">
          <img src="images/ban2.jpeg" alt="Offer 2">
        </div>
      </div>
      <div class="banner-buttons">
        <button class="prev-button">&#8249;</button>
        <button class="next-button">&#8250;</button>
      </div>
    </div>
    <div class="content-section">
  <h2>Welcome <?php echo $_SESSION['username']; ?> </h2>
  <p>Infinity Store est votre destination ultime pour trouver les meilleures offres et promotions en ligne. Que vous cherchiez des vêtements, des appareils électroniques, des articles ménagers ou bien d'autres produits, nous avons ce qu'il vous faut.</p>
  <p>Nous travaillons en collaboration avec des marchands réputés pour vous offrir des offres exclusives et des remises exceptionnelles. Parcourez notre vaste sélection de produits et profitez de la commodité de faire vos achats en ligne, directement depuis chez vous.</p>
  <p>Naviguez à travers nos catégories, découvrez les dernières tendances et économisez sur vos achats. Chez DealStore, nous mettons un point d'honneur à vous offrir une expérience d'achat agréable et satisfaisante.</p>
  <p>Commencez dès maintenant à explorer nos offres incroyables et préparez-vous à réaliser des économies substantielles sur vos achats en ligne.</p>
</div>

  </div>
  <script src="script.js"></script>
</body>
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

</html>
