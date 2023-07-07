<?php
// Inclure le fichier session.php
include 'session.php';
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

    <div class="sell-form-container">
      <h2>Déposer un item à vendre</h2>
      <form action="process_sell.php" method="POST" enctype="multipart/form-data">
        <label for="category">Catégorie:</label>
        <select name="category" id="category" required>
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

          // Récupérer les catégories de la base de données
          $sql = "SELECT * FROM category";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              echo '<option value="' . $row["name"] . '">' . $row["name"] . '</option>';
            }
          }

          // Fermer la connexion à la base de données
          $conn->close();
          ?>
        </select>

        <label for="name">Nom:</label>
        <input type="text" name="name" id="name" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea>

        <label for="photo">Photo:</label>
        <input type="file" name="photo" id="photo" accept="image/*" required>

        <label for="price">Prix:</label>
        <input type="number" name="price" id="price" step="0.01" required>

        <label for="stock">Stock:</label>
        <input type="text" name="stock" id="stock" required>

        <label for="sale_type">Type de vente:</label>
        <select name="sale_type" id="sale_type" required>
         ```php
          <option value="buy_now">Achat immédiat</option>
          <option value="auction">Enchères</option>
          <option value="best_offer">Meilleure offre</option>
        </select>

        <button type="submit" name="submit">Déposer</button>
      </form>
    </div>

    <div class="user-items-container">
      <h2>History</h2>
      <div class="user-items-container">
  <h2>Items mis en vente par l'utilisateur</h2>
  <?php
  $servername = "localhost";
          $username = "root";
          $password = "root";
          $dbname = "infinitydb";

          $conn = new mysqli($servername, $username, $password, $dbname);
          if ($conn->connect_error) {
            die("Connexion échouée : " . $conn->connect_error);
          }
  // Vérifier si l'utilisateur est connecté
  if (isset($_SESSION['user_id'])) {
    // Récupérer l'ID de l'utilisateur à partir de la session
    $user_id = $_SESSION['user_id'];

    // Récupérer les items mis en vente par l'utilisateur avec le statut "Progress"
    $sql_progress = "SELECT * FROM Item WHERE user_id = $user_id AND status = 'progress'";
    $result_progress = $conn->query($sql_progress);

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

<div class="user-items-container">
  <h2>Items vendus par l'utilisateur</h2>
  <?php
  $servername = "localhost";
          $username = "root";
          $password = "root";
          $dbname = "infinitydb";

          $conn = new mysqli($servername, $username, $password, $dbname);
          if ($conn->connect_error) {
            die("Connexion échouée : " . $conn->connect_error);
          }
  // Vérifier si l'utilisateur est connecté
  if (isset($_SESSION['user_id'])) {
    // Récupérer l'ID de l'utilisateur à partir de la session
    $user_id = $_SESSION['user_id'];

    // Récupérer les items vendus par l'utilisateur avec le statut "Sold"
    $sql_sold = "SELECT * FROM Item WHERE user_id = $user_id AND status = 'sold'";
    $result_sold = $conn->query($sql_sold);

    if ($result_sold->num_rows > 0) {
      while ($row = $result_sold->fetch_assoc()) {
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
      echo "Aucun item vendu avec le statut 'Sold'.";
    }
  }
  ?>
</div>

    </div>
  </div>

  <script src="script.js">
    function deleteItem(itemId) {
      // Demander une confirmation avant de supprimer l'item
      if (confirm("Voulez-vous vraiment supprimer cet item ?")) {
        // Effectuer la suppression de l'item en utilisant une requête AJAX
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_item.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
          if (xhr.readyState === 4 && xhr.status === 200) {
            // Afficher une confirmation de suppression
            alert("L'item a été supprimé avec succès.");

            // Actualiser la page pour afficher les changements
            location.reload();
          }
        };
        xhr.send("item_id=" + itemId);
      }
    }
  </script>
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
