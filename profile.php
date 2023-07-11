<?php
session_start();

// Vérifier si l'utilisateur est déjà connecté
if (!isset($_SESSION['username'])) {
  header('Location: account.php');
  exit;
}

// Traitement de la déconnexion
if (isset($_GET['logout'])) {
  session_destroy();
  header('Location: account.php');
  exit;
}

// Établir la connexion à la base de données
$host = 'localhost'; // Remplacez par l'adresse de votre serveur de base de données
$username = 'root'; // Remplacez par votre nom d'utilisateur de base de données
$password = 'root'; // Remplacez par votre mot de passe de base de données
$database = 'infinitydb'; // Remplacez par le nom de votre base de données

$connection = mysqli_connect($host, $username, $password, $database);

if (!$connection) {
  die('Erreur de connexion à la base de données : ' . mysqli_connect_error());
}

$userId = $_SESSION['user_id'];
$query = "SELECT * FROM User WHERE user_id = $userId";
$result = mysqli_query($connection, $query);
$user = mysqli_fetch_assoc($result);

// Récupérer les commandes de l'utilisateur à partir de la base de données
$query = "SELECT * FROM orders WHERE user_id = $userId";
$result = mysqli_query($connection, $query);
$orders = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Vérifier si l'utilisateur est administrateur
$isAdministrator = ($user['role'] === 'admin');

// Vérifier si le formulaire pour ajouter/supprimer un item a été soumis
if ($isAdministrator && isset($_POST['addItem'])) {
  $itemName = $_POST['itemName'];
  // Ajouter le nouvel item à la base de données
  $addItemQuery = "INSERT INTO Item (item_name) VALUES ('$itemName')";
  $addItemResult = mysqli_query($connection, $addItemQuery);
}

if ($isAdministrator && isset($_POST['deleteItem'])) {
  $itemId = $_POST['itemId'];
  // Supprimer l'item de la base de données
  $deleteItemQuery = "DELETE FROM Item WHERE item_id = '$itemId'";
  $deleteItemResult = mysqli_query($connection, $deleteItemQuery);
}

// Vérifier si le formulaire pour modifier le rôle/supprimer un utilisateur a été soumis
if ($isAdministrator && isset($_POST['updateRole'])) {
  $selectedUserId = $_POST['selectedUserId'];
  $selectedRole = $_POST['selectedRole'];
  // Mettre à jour le rôle de l'utilisateur dans la base de données
  $updateRoleQuery = "UPDATE User SET role = '$selectedRole' WHERE user_id = '$selectedUserId'";
  $updateRoleResult = mysqli_query($connection, $updateRoleQuery);
}

if ($isAdministrator && isset($_POST['deleteUser'])) {
  $selectedUserId = $_POST['selectedUserId'];
  // Supprimer l'utilisateur de la base de données
  $deleteUserQuery = "DELETE FROM User WHERE user_id = '$selectedUserId'";
  $deleteUserResult = mysqli_query($connection, $deleteUserQuery);
}

// Fermer la connexion à la base de données
mysqli_close($connection);
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
      <a href="profile.php?logout=true">
        <div class="user-link">
          <img src="logo/logout.png" alt="Logout">
         <span><b>Logout</b></span>
        </div>
      </a>
    </div>
  </header>

  <div class="container">
    <div class="scrolling-text">
      <span class="message flash-sale">Vente flash</span>
      <span class="message promo-code">CODE PROMO : SOLDE</span>
    </div>
    <div class="profile-page">
      <div class="menu">
        <ul>
          <li><a href="?page=info">Informations</a></li>
          <?php
          // Vérifier le rôle de l'utilisateur
          if ($user['role'] === 'customer') {
            echo '<li><a href="?page=orders">Mes commandes</a></li>';
          } elseif ($user['role'] === 'seller') {
            echo '<li><a href="?page=orders">Mes commandes</a></li>';
            echo '<li><a href="?page=seller">Seller</a></li>';
          } elseif ($user['role'] === 'admin') {
            echo '<li><a href="?page=orders">Mes commandes</a></li>';
            echo '<li><a href="?page=admin">Administration</a></li>';
          }
          ?>
        </ul>
      </div>
      <div class="content">
       <?php
       $host = 'localhost'; // Remplacez par l'adresse de votre serveur de base de données
$username = 'root'; // Remplacez par votre nom d'utilisateur de base de données
$password = 'root'; // Remplacez par votre mot de passe de base de données
$database = 'infinitydb'; // Remplacez par le nom de votre base de données

$connection = mysqli_connect($host, $username, $password, $database);

if (!$connection) {
  die('Erreur de connexion à la base de données : ' . mysqli_connect_error());
}
// Afficher la section "Informations" par défaut si le paramètre 'page' n'est pas défini
if (!isset($_GET['page'])) {
  echo '<h2>Informations</h2>';

  // Vérifier si le formulaire a été soumis
  if (isset($_POST['submit'])) {
    // Récupérer les valeurs du formulaire
    $newUsername = $_POST['username'];
    $newEmail = $_POST['email'];

    // Mettre à jour les informations de l'utilisateur dans la base de données
    $updateQuery = "UPDATE User SET username = '$newUsername', email = '$newEmail' WHERE user_id = $userId";
    $updateResult = mysqli_query($connection, $updateQuery);

    if ($updateResult) {
      echo '<p class="success-message">Les informations ont été mises à jour avec succès.</p>';

      // Mettre à jour les informations affichées à l'écran
      $user['username'] = $newUsername;
      $user['email'] = $newEmail;
    } else {
      echo '<p class="error-message">Erreur lors de la mise à jour des informations.</p>';
    }
  }

  // Afficher le formulaire avec les informations de l'utilisateur
  echo '<form method="POST" action="?page=info" class="vertical-form">';
  echo '<div class="form-group">';
  echo '<label for="username">Username:</label>';
  echo '<input type="text" id="username" name="username" value="' . $user['username'] . '">';
  echo '</div>';
  echo '<div class="form-group">';
  echo '<label for="email">Email:</label>';
  echo '<input type="email" id="email" name="email" value="' . $user['email'] . '">';
  echo '</div>';
  echo '<div class="form-group">';
  echo '<label for="role">Rôle:</label>';
  echo '<input type="text" id="role" name="role" value="' . $user['role'] . '" readonly>';
  echo '</div>';

  echo '<div class="form-group">';
  echo '<input type="submit" name="submit" value="Enregistrer les modifications">';
  echo '</div>';
  echo '</form>';
}

        // Afficher les informations de l'utilisateur
        if (isset($_GET['page']) && $_GET['page'] === 'info') {
          echo '<h2>Informations</h2>';

          // Vérifier si le formulaire a été soumis
          if (isset($_POST['submit'])) {
            // Récupérer les valeurs du formulaire
            $newUsername = $_POST['username'];
            $newEmail = $_POST['email'];

            // Mettre à jour les informations de l'utilisateur dans la base de données
            $updateQuery = "UPDATE User SET username = '$newUsername', email = '$newEmail' WHERE user_id = $userId";
            $updateResult = mysqli_query($connection, $updateQuery);

            if ($updateResult) {
              echo '<p class="success-message">Les informations ont été mises à jour avec succès.</p>';

              // Mettre à jour les informations affichées à l'écran
              $user['username'] = $newUsername;
              $user['email'] = $newEmail;
            } else {
              echo '<p class="error-message">Erreur lors de la mise à jour des informations.</p>';
            }
          }

          // Afficher le formulaire avec les informations de l'utilisateur
          echo '<form method="POST" action="?page=info" class="vertical-form">';
          echo '<div class="form-group">';
          echo '<label for="username">Username:</label>';
          echo '<input type="text" id="username" name="username" value="' . $user['username'] . '">';
          echo '</div>';
          echo '<div class="form-group">';
          echo '<label for="email">Email:</label>';
          echo '<input type="email" id="email" name="email" value="' . $user['email'] . '">';
          echo '</div>';
          echo '<div class="form-group">';
echo '<label for="role">Rôle:</label>';
echo '<input type="text" id="role" name="role" value="' . $user['role'] . '" readonly>';
echo '</div>';

          echo '<div class="form-group">';
          echo '<input type="submit" name="submit" value="Enregistrer les modifications">';
          echo '</div>';
          echo '</form>';
        }

        if (isset($_GET['page']) && $_GET['page'] === 'orders') {
        foreach ($orders as $order) {
  echo '<div class="order-container">';
  echo '<div class="order-id">Commande #' . $order['order_id'] . '</div>';
  echo '<div class="order-id">Total $' . $order['amount'] . '</div>';
  echo '<div class="order-date">' . $order['purchase_date'] . '</div>';
  echo '</div>';

  // Récupérer les produits associés à cette commande
  $orderId = $order['order_id'];
  $cartItems = mysqli_query($connection, "SELECT * FROM cart WHERE order_id = $orderId");
  if ($cartItems && mysqli_num_rows($cartItems) > 0) {
    echo '<table class="order-table">';
    while ($item = mysqli_fetch_assoc($cartItems)) {
      $itemId = $item['item_id'];
      $itemInfo = mysqli_query($connection, "SELECT * FROM Item WHERE item_id = $itemId");
      $itemData = mysqli_fetch_assoc($itemInfo);

      echo '<tr>';
      echo '<td>Nom du produit: ' . $itemData['name'] . '</td>';
      echo '<td>Prix: ' . $itemData['price'] . '</td>';
      echo '</tr>';
    }
    echo '</table>';
  } else {
    echo '<p>Aucun produit trouvé.</p>';
  }
}
}




        if (isset($_GET['page']) && $_GET['page'] === 'seller' && $user['role'] === 'seller') {
  echo '<h2>Seller</h2>';

  echo '<div class="seller-options">';
  echo '<a href="seller.php">Ma page de vendeur</a>';
  echo '<a href="seller.php?seller_id=' . $user['user_id'] . '">Ma page de vendeur</a>';
  echo '</div>';
}


        // Afficher la section "Administration" pour les administrateurs
        if (isset($_GET['page']) && $_GET['page'] === 'admin'&& $user['role'] === 'admin') {
          echo '<h2>Administration</h2>';
          // Code HTML spécifique pour la section Administration
          ?>

          <h3>Gestionnaire d'items</h3>
<form method="POST" action="" class="vertical-form">
  <div class="form-group">
    <label for="selectedItemId">Sélectionner un item:</label>
    <select id="selectedItemId" name="selectedItemId" required>
      <?php
      $itemsQuery = "SELECT * FROM Item";
      $itemsResult = mysqli_query($connection, $itemsQuery);
      $items = mysqli_fetch_all($itemsResult, MYSQLI_ASSOC);

      foreach ($items as $item) {
        echo '<option value="' . $item['item_id'] . '">' . $item['item_id'] . ' - ' . $item['name'] . '</option>';
      }
      ?>
    </select>
  </div>
  <div class="form-group">
    <input type="submit" name="deleteItem" value="Supprimer l'item">
  </div>
</form>

<?php
$host = 'localhost'; // Remplacez par l'adresse de votre serveur de base de données
$username = 'root'; // Remplacez par votre nom d'utilisateur de base de données
$password = 'root'; // Remplacez par votre mot de passe de base de données
$database = 'infinitydb'; // Remplacez par le nom de votre base de données

$connection = mysqli_connect($host, $username, $password, $database);

if (!$connection) {
  die('Erreur de connexion à la base de données : ' . mysqli_connect_error());
}
// Vérifier si le formulaire de suppression a été soumis
if (isset($_POST['deleteItem'])) {
  $selectedItemId = $_POST['selectedItemId'];

  // Supprimer l'item de la base de données
  $deleteQuery = "DELETE FROM Item WHERE item_id = $selectedItemId";
  $deleteResult = mysqli_query($connection, $deleteQuery);

  if ($deleteResult) {
    echo '<p class="success-message">L\'item a été supprimé avec succès.</p>';
  } else {
    echo '<p class="error-message">Erreur lors de la suppression de l\'item.</p>';
  }
}
?>
<hr class="separator">



          <h3>Gestionnaire d'utilisateurs</h3>
          <form method="POST" action="?page=admin" class="vertical-form">
            <div class="form-group">
              <label for="selectedUserId">Sélectionner un utilisateur:</label>
              <select id="selectedUserId" name="selectedUserId" required>
                <?php
                $usersQuery = "SELECT * FROM User";
                $usersResult = mysqli_query($connection, $usersQuery);
                $users = mysqli_fetch_all($usersResult, MYSQLI_ASSOC);

                foreach ($users as $user) {
                  echo '<option value="' . $user['user_id'] . '">' . $user['username'] . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label for="selectedRole">Sélectionner le rôle:</label>
              <select id="selectedRole" name="selectedRole" required>
                <option value="customer">Customer</option>
                <option value="seller">Seller</option>
                <option value="admin">Admin</option>
              </select>
            </div>
            <div class="form-group">
              <input type="submit" name="updateRole" value="Modifier le rôle">
            </div>
            <div class="form-group">
              <input type="submit" name="deleteUser" value="Supprimer l'utilisateur">
            </div>
          </form>

          <?php
        }
        
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
