<?php
// Include the session.php file
include 'session.php';
include 'db_connect.php';

// Retrieve categories from the database
$query = "SELECT * FROM category";
$result = mysqli_query($connection, $query);

if (!$result) {
    die('Error retrieving categories: ' . mysqli_error($connection));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="styles.css">

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
              <li><a href="categories.php">Car</a></li>
              <li><a href="categories.php">Moto</a></li>
              <li><a href="categories.php">Clothing</a></li>
            </ul>
          </li>
          <li class="menu-item buy-menu-item">
            <a href="buy.php">Buy</a>
            <ul class="sub-menu">
              <li><a href="buy.php">All</a></li>
              <li><a href="buy.php">Buy it now</a></li>
              <li><a href="buy.php">Auction</a></li>
              <li><a href="buy.php">Best offers</a></li>
            </ul>
          </li>
          <li class="menu-item"><a href="sell.php">Sell</a></li>
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
      // Check if the user is logged in
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
      <span class="message flash-sale">Flash Message!</span>
      <span class="message promo-code">New INFINITY Store website</span>
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
      <h2>Welcome <?php echo $_SESSION['username']; ?></h2>
      <p>Categories you might like:</p>
      <div class="category-grid">
        <a href="category.php?category=Car" class="category-item">
          <img src="images/all-categories.png" alt="Car" >
          <span>Car</span>
        </a>
        <a href="category.php?category=Moto" class="category-item">
          <img src="images/electronics.png" alt="Moto">
          <span>Moto</span>
        </a>
        <a href="category.php?category=clothing" class="category-item">
          <img src="images/clothing.png" alt="Clothing">
          <span>Clothing</span>
        </a>
        <a href="categories.php" class="category-item">
          <img src="images/clothing.png" alt="All">
          <span>View All</span>
        </a>
      </div>
    </div>
  </div>
  <script src="script.js"></script>
</body>
<footer>
  <div class="footer-container">
    <div class="footer-section">
      <h4>About us</h4>
      <p>We are 2 students who have invested all our lives in INFINITY Store</p>
    </div>
    <div class="footer-section">
      <h4>Contact</h4>
      <p>Email: support@infinity.com</p>
      <p>Phone: 123-456-7890</p>
    </div>
    <div class="footer-section">
      <h4>Useful links</h4>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="categories.php">Categories</a></li>
        <li><a href="buy.php">Buy</a></li>
        <li><a href="sell.php">Sell</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <p>All rights reserved &copy; 2023 - INFINITY Store</p>
  </div>
</footer>
</html>
