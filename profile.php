<?php
include 'session.php';

// Vérifier si l'utilisateur est déjà connecté
if (!isset($_SESSION['user_id'])) {
  header('Location: account.php');
  exit;
}

// Traitement de la déconnexion
if (isset($_GET['logout'])) {
  session_destroy();
  header('Location: account.php');
  exit;
}

include 'db_connect.php';

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
    <div class="profile-page">
      <div class="menu">
        <ul>
          <li><a href="?page=info">Informations</a></li>
          <?php
          // Vérifier le rôle de l'utilisateur
          if ($user['role'] === 'customer') {
            echo '<li><a href="?page=orders">My orders</a></li>';
            echo '<li><a href="?page=auction">Auction</a></li>';
            echo '<li><a href="?page=offer">Best Offer</a></li>';
          } elseif ($user['role'] === 'seller') {
            echo '<li><a href="?page=orders">My orders</a></li>';
            echo '<li><a href="?page=auction">Auction</a></li>';
            echo '<li><a href="?page=offer">Best Offer</a></li>';
            echo '<li><a href="?page=seller">Seller</a></li>';
          } elseif ($user['role'] === 'admin') {
            echo '<li><a href="?page=orders">My orders</a></li>';
            echo '<li><a href="?page=auction">Auction</a></li>';
            echo '<li><a href="?page=offer">Best Offer</a></li>';
            echo '<li><a href="?page=admin">Administration</a></li>';
          }
          ?>
        </ul>
      </div>
      <div class="content">
        <?php
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
        if (isset($_GET['page']) && $_GET['page'] === 'offer') {
  $userId = $_SESSION['user_id'];

  // Récupérer les offres en attente
  $pendingQuery = "SELECT do.*, u.username, i.photo FROM direct_offers do
  INNER JOIN User u ON do.seller_id = u.user_id
  INNER JOIN Item i ON do.item_id = i.item_id
  WHERE (do.status = 'pending' OR do.status = 'counter-offer') AND do.sender = '$userId'";
$pendingOffers = mysqli_query($connection, $pendingQuery);

// Récupérer les offres rejetées
$rejectedQuery = "SELECT do.*, u.username, i.photo FROM direct_offers do
  INNER JOIN User u ON do.seller_id = u.user_id
  INNER JOIN Item i ON do.item_id = i.item_id
  WHERE do.status = 'rejected' AND do.user_id = '$userId'";
$rejectedOffers = mysqli_query($connection, $rejectedQuery);

// Récupérer les offres acceptées
$acceptedQuery = "SELECT do.*, u.username, i.photo FROM direct_offers do
  INNER JOIN User u ON do.seller_id = u.user_id
  INNER JOIN Item i ON do.item_id = i.item_id
  WHERE do.status = 'accepted' AND do.user_id = '$userId'";
$acceptedOffers = mysqli_query($connection, $acceptedQuery);

// Récupérer les contre-offres
$counterQuery = "SELECT do.*, u.username, i.photo FROM direct_offers do
  INNER JOIN User u ON do.seller_id = u.user_id
  INNER JOIN Item i ON do.item_id = i.item_id
  WHERE do.status = 'counter-offer' AND do.receiver = '$userId'";
$counterOffers = mysqli_query($connection, $counterQuery);

  // Afficher le tableau des offres en attente s'il y en a
  if (mysqli_num_rows($pendingOffers) > 0) {
    echo '<h2>Offres en attente</h2>';
    echo '<table class="offers-table">';
    echo '<tr>
            <th>Photo</th>
            <th>Offre</th>
            <th>ID de l\'article</th>
            <th>ID de l\'utilisateur</th>
            <th>Prix</th>
            <th>Horodatage</th>
            <th>Statut</th>
            <th>Vendeur</th>
          </tr>';

    while ($offer = mysqli_fetch_assoc($pendingOffers)) {
        echo '<tr>';
        echo '<td><img src="data:image/jpeg;base64,' . base64_encode($offer['photo']) . '" alt="Photo" height="100"></td>';
        echo '<td>' . $offer['offer_id'] . '</td>';
        echo '<td>' . $offer['item_id'] . '</td>';
        echo '<td>' . $offer['user_id'] . '</td>';
        echo '<td>$' . $offer['price'] . '</td>';
        echo '<td>' . $offer['timestamp'] . '</td>';
        echo '<td>' . $offer['status'] . '</td>';
        echo '<td>' . $offer['username'] . '</td>';
        echo '</tr>';

        // Récupérer les informations sur l'article associé à l'offre
        $itemId = $offer['item_id'];
        $itemInfo = mysqli_query($connection, "SELECT * FROM Item WHERE item_id = $itemId");
        $itemData = mysqli_fetch_assoc($itemInfo);

        echo '<tr>';
        echo '<td colspan="8">';
        echo '<strong>Informations sur l\'article:</strong>';
        echo 'Nom de l\'article: ' . $itemData['name'] . ', Prix d\'origine: $' . $itemData['price'];
        echo '</td>';
        echo '</tr>';
    }

    echo '</table>';
}

  // Afficher le tableau des offres rejetées s'il y en a
  if (mysqli_num_rows($rejectedOffers) > 0) {
      echo '<h2>Offres rejetées</h2>';
      echo '<table class="offers-table">';
      echo '<tr>
              <th>Photo</th>
              <th>Offre</th>
              <th>ID de l\'article</th>
              <th>ID de l\'utilisateur</th>
              <th>Prix</th>
              <th>Horodatage</th>
              <th>Statut</th>
              <th>Vendeur</th>
            </tr>';

      while ($offer = mysqli_fetch_assoc($rejectedOffers)) {
          echo '<tr>';
          echo '<td><img src="data:image/jpeg;base64,' . base64_encode($offer['photo']) . '" alt="Photo" height="100"></td>';
          echo '<td>' . $offer['offer_id'] . '</td>';
          echo '<td>' . $offer['item_id'] . '</td>';
          echo '<td>' . $offer['user_id'] . '</td>';
          echo '<td>$' . $offer['price'] . '</td>';
          echo '<td>' . $offer['timestamp'] . '</td>';
          echo '<td>' . $offer['status'] . '</td>';
          echo '<td>' . $offer['username'] . '</td>';

          echo '</tr>';

          // Récupérer les informations sur l'article associé à l'offre
          $itemId = $offer['item_id'];
          $itemInfo = mysqli_query($connection, "SELECT * FROM Item WHERE item_id = $itemId");
          $itemData = mysqli_fetch_assoc($itemInfo);

          echo '<tr>';
          echo '<td colspan="8">';
          echo '<strong>Informations sur l\'article:</strong>';
          echo 'Nom de l\'article: ' . $itemData['name'] . ', Prix d\'origine: $' . $itemData['price'];
          echo '</td>';
          echo '</tr>';
      }

      echo '</table>';
  }

  // Afficher le tableau des offres acceptées s'il y en a
  if (mysqli_num_rows($acceptedOffers) > 0) {
      echo '<h2>Offres acceptées</h2>';
      echo '<table class="offers-table">';
      echo '<tr>
              <th>Photo</th>X
              <th>Offre</th>
              <th>ID de l\'article</th>
              <th>ID de l\'utilisateur</th>
              <th>Prix</th>
              <th>Horodatage</th>
              <th>Statut</th>
              <th>Vendeur</th>
            </tr>';

      while ($offer = mysqli_fetch_assoc($acceptedOffers)) {
          echo '<tr>';
          echo '<td><img src="data:image/jpeg;base64,' . base64_encode($offer['photo']) . '" alt="Photo" height="100"></td>';
          echo '<td>' . $offer['offer_id'] . '</td>';
          echo '<td>' . $offer['item_id'] . '</td>';
          echo '<td>' . $offer['user_id'] . '</td>';
          echo '<td>$' . $offer['price'] . '</td>';
          echo '<td>' . $offer['timestamp'] . '</td>';
          echo '<td>' . $offer['status'] . '</td>';
          echo '<td>' . $offer['username'] . '</td>';

          echo '</tr>';

          // Récupérer les informations sur l'article associé à l'offre
          $itemId = $offer['item_id'];
          $itemInfo = mysqli_query($connection, "SELECT * FROM Item WHERE item_id = $itemId");
          $itemData = mysqli_fetch_assoc($itemInfo);

          echo '<tr>';
          echo '<td colspan="8">';
          echo '<strong>Informations sur l\'article:</strong>';
          echo 'Nom de l\'article: ' . $itemData['name'] . ', Prix d\'origine: $' . $itemData['price'];
          echo '</td>';
          echo '</tr>';
      }

      echo '</table>';
  }

  // Afficher le tableau des contre-offres s'il y en a
  if (mysqli_num_rows($counterOffers) > 0) {
    echo '<h2>Contre-offres</h2>';
    echo '<table class="offers-table">';
    echo '<tr>
            <th>Photo</th>
            <th>Offre</th>
            <th>ID de l\'article</th>
            <th>ID de l\'utilisateur</th>
            <th>Prix</th>
            <th>Horodatage</th>
            <th>Statut</th>
            <th>Vendeur</th>
            <th>Actions</th>
          </tr>';

    while ($offer = mysqli_fetch_assoc($counterOffers)) {
      echo '<tr>';
echo '<td><img src="data:image/jpeg;base64,' . base64_encode($offer['photo']) . '" alt="Photo" height="100"></td>';
echo '<td>' . $offer['offer_id'] . '</td>';
echo '<td>' . $offer['item_id'] . '</td>';
echo '<td>' . $offer['user_id'] . '</td>';
echo '<td>$' . $offer['price'] . '</td>';
echo '<td>' . $offer['timestamp'] . '</td>';
echo '<td>' . $offer['status'] . '</td>';
echo '<td>' . $offer['username'] . '</td>';
echo '<td>
    <form action="status_offer.php" method="post">
        <input type="hidden" name="offer_id" value="' . $offer['offer_id'] . '">
        <input type="submit" name="accept_offer" value="Accepter">
    </form>
    <form action="status_offer.php" method="post">
        <input type="hidden" name="offer_id" value="' . $offer['offer_id'] . '">
        <input type="submit" name="reject_offer" value="Rejeter">
    </form>
    <form action="status_offer.php" method="post">
        <input type="hidden" name="offer_id" value="' . $offer['offer_id'] . '">
        <label>Contre-offre: $</label>
        <input type="number" name="counter_offer_amount" step="0.01" required>
        <input type="submit" name="counter_offer" value="Contre-offre">
    </form>
</td>';
echo '</tr>';


        // Récupérer les informations sur l'article associé à l'offre
        $itemId = $offer['item_id'];
        $itemInfo = mysqli_query($connection, "SELECT * FROM Item WHERE item_id = $itemId");
        $itemData = mysqli_fetch_assoc($itemInfo);

        echo '<tr>';
        echo '<td colspan="9">';
        echo '<strong>Informations sur l\'article:</strong>';
        echo 'Nom de l\'article: ' . $itemData['name'] . ', Prix d\'origine: $' . $itemData['price'];
        echo '</td>';
        echo '</tr>';
    }

    echo '</table>';
}
}
   


if (isset($_GET['page']) && $_GET['page'] === 'auction') {
  // Récupérer l'ID de l'utilisateur actuel
  $userId = $_SESSION['user_id'];

  // Récupérer les enchères "finish" où l'utilisateur est le gagnant
  $finishBids = mysqli_query($connection, "SELECT * FROM auction WHERE status = 'finish' AND winner_id = $userId");

  // Afficher les enchères "finish" où l'utilisateur est le gagnant
  if ($finishBids && mysqli_num_rows($finishBids) > 0) {
      echo '<h2>Enchères terminées remportées par l\'utilisateur :</h2>';
      echo '<table>';
      echo '<tr><th>Image</th><th>Numéro d\'article</th><th>Numéro d\'enchère</th><th>Montant</th></tr>';
      while ($bid = mysqli_fetch_assoc($finishBids)) {
          $itemId = $bid['item_id'];
          $itemInfo = mysqli_query($connection, "SELECT * FROM item WHERE item_id = $itemId");
          $itemData = mysqli_fetch_assoc($itemInfo);

          echo '<tr>';
          echo '<td><img src="data:image/jpeg;base64,' . base64_encode($itemData['photo']) . '" alt="Image de l\'article" width="100"></td>';
          echo '<td>' . $itemData['item_id'] . '</td>';
          echo '<td>' . $bid['auction_id'] . '</td>';
          echo '<td>' . $bid['highest_bid'] . '</td>';
          echo '</tr>';
      }
      echo '</table>';
  } else {
      echo '<p>Aucune enchère "finish" trouvée pour cet utilisateur.</p>';
  }

  // Récupérer les enchères "ongoing" de l'utilisateur avec le montant maximum
  $ongoingBids = mysqli_query($connection, "SELECT auction.item_id, auction.auction_id, MAX(user_bid.amount) AS user_max_bid, MAX(auction_bid.amount) AS auction_max_bid
    FROM auction
    LEFT JOIN (SELECT item_id, MAX(amount) AS amount FROM bid WHERE user_id = $userId GROUP BY item_id) AS user_bid ON auction.item_id = user_bid.item_id
    LEFT JOIN (SELECT item_id, MAX(amount) AS amount FROM bid GROUP BY item_id) AS auction_bid ON auction.item_id = auction_bid.item_id
    WHERE auction.status = 'ongoing' AND auction.item_id IN (SELECT DISTINCT item_id FROM bid WHERE user_id = $userId)
    GROUP BY auction.item_id");

// Afficher les enchères "ongoing" de l'utilisateur avec les montants maximaux
if ($ongoingBids && mysqli_num_rows($ongoingBids) > 0) {
    echo '<h2>Enchères en cours de l\'utilisateur :</h2>';
    echo '<table>';
    echo '<tr><th>Image</th><th>Numéro d\'article</th><th>Numéro d\'enchère</th><th>Montant maximal de l\'utilisateur</th><th>Montant maximal de l\'enchère</th></tr>';
    while ($bid = mysqli_fetch_assoc($ongoingBids)) {
        $itemId = $bid['item_id'];
        $itemInfo = mysqli_query($connection, "SELECT * FROM item WHERE item_id = $itemId");
        $itemData = mysqli_fetch_assoc($itemInfo);

        echo '<tr>';
        echo '<td><img src="data:image/jpeg;base64,' . base64_encode($itemData['photo']) . '" alt="Image de l\'article" width="100"></td>';
        echo '<td>' . $itemData['item_id'] . '</td>';
        echo '<td>' . $bid['auction_id'] . '</td>';
        echo '<td>' . $bid['user_max_bid'] . '</td>';
        echo '<td>' . $bid['auction_max_bid'] . '</td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo '<p>Aucune enchère "ongoing" trouvée pour cet utilisateur.</p>';
}



}




        if (isset($_GET['page']) && $_GET['page'] === 'seller' && $user['role'] === 'seller') {
          echo '<h2>Seller</h2>';

          echo '<div class="seller-options">';
          echo '<a href="sell.php">Sell an item</a>';
          echo '<a href="seller.php?seller_id=' . $user['user_id'] . '">My seller page</a>';
          echo '</div>';
        }

        // Afficher la section "Administration" pour les administrateurs
        if (isset($_GET['page']) && $_GET['page'] === 'admin' && $user['role'] === 'admin') {
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
                  echo '<option value="' . $item['item_id'] . '">' . $item['item_id'] . ' - ' . $item['item_name'] . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <input type="submit" name="deleteItem" value="Supprimer l'item">
            </div>
          </form>

          <?php
          include 'db_connect.php';
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

