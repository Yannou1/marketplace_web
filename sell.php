<?php
// Inclure le fichier session.php
include 'session.php';
// Démarrer la session

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
      <span class="message flash-sale">Flash Message !</span>
      <span class="message promo-code">New INFINITY Store website</span>
    </div>

    <div class="sell-form-container">
      <h2>Déposer un item à vendre</h2>
      <form action="process_sell.php" method="POST" enctype="multipart/form-data">
        <label for="category">Catégorie:</label>
        <select name="category" id="category" required>
          <?php
          // Connexion à la base de données
          include 'db_connect.php';

          // Récupérer les catégories de la base de données
          $sql = "SELECT * FROM category";
          $result = $connection->query($sql);

          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              echo '<option value="' . $row["name"] . '">' . $row["name"] . '</option>';
            }
          }

          // Fermer la connexion à la base de données
          $connection->close();
          ?>
        </select>
        <label for="sale_type">Type de vente:</label>
        <select name="sale_type" id="sale_type" required>
          <option value="buy_now">Achat immédiat</option>
          <option value="auction">Enchères</option>
          <option value="best_offer">Meilleure offre</option>
        </select>
        <label for="name">Nom:</label>
        <input type="text" name="name" id="name" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea>

        <label for="photo">Photo:</label>
        <input type="file" name="photo" id="photo" accept="image/*" required>
        <div class = "moovable-fields">
        <label for="price">Prix:</label>
        <input type="number" name="price" id="price" step="0.01" >
          <br><br>
        <label for="stock">Stock:</label>
        <input type="text" name="stock" id="stock" >
        </div>

<!-- Cases supplémentaires pour les enchères -->
<div class="additional-fields" style="display: none;">
  <label for="minimum_bid">Prix minimum des enchères:</label>
  <input type="number" name="minimum_bid" id="minimum_bid">
  <label for="start_date">Date and Time of Start:</label>
  <input type="datetime-local" name="start_date" id="start_date">
  <label for="end_date">Date and Time of End:</label>
  <input type="datetime-local" name="end_date" id="end_date">

</div>

        <button type="submit" name="submit">Déposer</button>
      </form>
    </div>

    <div class="user-items-container">
      <div class="user-items-container">
      <h2>History</h2>
      <div class="user-items-container">
  <h2>Items mis en vente par l'utilisateur</h2>
  <?php
 include 'db_connect.php';
  // Vérifier si l'utilisateur est connecté
  if (isset($_SESSION['user_id'])) {
    // Récupérer l'ID de l'utilisateur à partir de la session
    $user_id = $_SESSION['user_id'];

    // Récupérer les items mis en vente par l'utilisateur avec le statut "Progress"
    $sql_progress = "SELECT * FROM Item WHERE user_id = $user_id AND status = 'available'";
    $result_progress = $connection->query($sql_progress);

    if ($result_progress->num_rows > 0) {
      while ($row = $result_progress->fetch_assoc()) {
        $item_id = $row['item_id'];
        $item_name = $row['name'];
        $stock = $row['stock'];
        $price = $row['price'];
        $item_status = $row['status'];

        // Afficher les informations de l'item avec le bouton de suppression
        echo '<div class="user-item">';
        echo '<span>' . $item_name . '</span>';
        echo '<span>Stock: ' . $stock . '</span>';
        echo '<span>Prix: ' . $price . '</span>';
        echo '<img src="logo/trash.png" alt="Supprimer" class="delete-item-btn" onclick="deleteItem(' . $item_id . ')">';
        echo '</div>';
      }
    } else {
      echo "Aucun item vendu avec le statut 'Progress'.";
    }
  }
  ?>
</div>


    </div>
  </div>

  <script src="script.js">
</script>


<style>
  .hidden-fields {
    display: none;
  }
</style>



</body>
<footer>
  <div class="footer-container">
    <div class="footer-section">
      <h4>About us</h4>
      <p>We are 2 students who have invested all our lives in INFINITY Store</p>
    </div>
    <div class="footer-section">
      <h4>Contact</h4>
      <p>Email : support@infinity.com</p>
      <p>Phone : 123-456-7890</p>
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

