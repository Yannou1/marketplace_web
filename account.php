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
      session_start();

      if (isset($_SESSION['username'])) {
        echo '<a href="profile.php">';
      } else {
        echo '<a href="account.php">';
      }
      ?>
        <div class="user-link">
          <img src="logo/account.png" alt="Account">
          <span><b>Account</b></span>
        </div>
      </a>
    </div>
  </header>
  <div class="container">
    <div class="scrolling-text">
      <span class="message flash-sale">Vente flash</span>
      <span class="message promo-code">CODE PROMO : SOLDE</span>
    </div>

    <div class="container login-container">
      <div class="login-section">
        <h2>Login</h2>
        <?php
        if (isset($_SESSION['login_error'])) {
          echo '<p class="error-message">' . $_SESSION['login_error'] . '</p>';
          unset($_SESSION['login_error']);
        }
        ?>
        <form action="login.php" method="post">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" required>
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
          <button type="submit">Login</button>
        </form>
      </div>
      <div class="create-account-section">
        <h2>Je crée mon compte</h2>
        <p>En créant un compte, vous profitez de nombreux avantages INFINITY :</p>
        <br>
        <ul>
          <li>L’historique de vos commandes passées sur le site</li>
          <li>Les meilleures offres du moment</li>
          <li>Le meilleur site d'enchère</li>
        </ul>
        <a href="register.php"><button type="button">Register</button>
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
