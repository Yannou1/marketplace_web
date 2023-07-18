
<?php
// Démarrer la session
include 'session.php';
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

      <div id="item-detail">
  <div class="product-details">
    <?php
    include 'db_connect.php';
    // Récupérer l'ID du produit à partir du paramètre d'URL
    $item_id = $_GET['itemId'];

    // Exécuter la requête SELECT pour récupérer les informations du produit
    $sql = "SELECT Item.*, User.username FROM Item INNER JOIN User ON Item.user_id = User.user_id WHERE Item.item_id = $item_id";
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
      // Récupérer les informations du produit
      $row = $result->fetch_assoc();
      $product_details = array(
        'name' => $row['name'],
        'price' => $row['price'],
        'description' => $row['description'],
        'photo' => base64_encode($row['photo']), // Conversion en base64
        'stock' => $row['stock'],
        'seller' => $row['username'],
        'user_id' => $row['user_id'],
        'sale_type' => $row['sale_type']
      );

      if ($product_details['sale_type'] == 'buy_now') {
        // Afficher les informations du produit
        echo '<img src="data:image/jpeg;base64,' . $product_details['photo'] . '" alt="Product Image">';
        echo '<h2>' . $product_details['name'] . '</h2>';
        echo '<p>' . $product_details['description'] . '</p>';
        echo '<p>Price: $' . $product_details['price'] . '</p>';
        echo '<p>Stock: ' . $product_details['stock'] . '</p>';
        echo '<p>sale_type: ' . $product_details['sale_type'] . '</p>';

        // Ajouter le formulaire pour sélectionner la quantité
        echo '<form action="process_cart.php" method="POST">';
        echo '<input type="hidden" name="item_id" value="' . $item_id . '">';
        echo '<label for="quantity">Quantity:</label>';
        echo '<input type="number" name="quantity" id="quantity" min="1" max="' . $product_details['stock'] . '" value="1" required>';
        echo '<button type="submit" name="buy">Buy</button>';
        echo '</form>';
        echo '<p>Sold by: <a href="seller.php?seller_id=' . $product_details['user_id'] . '">' . $product_details['seller'] . '</a></p>';
      }

      if ($product_details['sale_type'] == 'best_offer') {
        // Afficher les informations du produit
        echo '<img src="data:image/jpeg;base64,' . $product_details['photo'] . '" alt="Product Image">';
        echo '<h2>' . $product_details['name'] . '</h2>';
        echo '<p>' . $product_details['description'] . '</p>';
        echo '<p>Price: $' . $product_details['price'] . '</p>';
        echo '<p>Stock: ' . $product_details['stock'] . '</p>';
        echo '<p>sale_type: ' . $product_details['sale_type'] . '</p>';

        // Ajouter le formulaire pour sélectionner la quantité
        echo '<form action="process_offer.php" method="POST">';
        echo '<input type="hidden" name="item_id" value="' . $item_id . '">';
        echo '<label for="quantity">Quantity:</label>';
        echo 'Bid Amount: <input type="number" name="offer" min="' . $product_details['price'] . '" required>';
        echo '<button type="submit" name="buy">Buy</button>';
        echo '</form>';
        echo '<p>Sold by: <a href="seller.php?seller_id=' . $product_details['user_id'] . '">' . $product_details['seller'] . '</a></p>';
      }

      // Pour les auctions
      if ($product_details['sale_type'] == 'auction') {
        // Afficher les informations du produit
        echo '<img src="data:image/jpeg;base64,' . $product_details['photo'] . '" alt="Product Image">';
        echo '<h2>' . $product_details['name'] . '</h2>';
        echo '<p>' . $product_details['description'] . '</p>';
        echo '<p>Price: $' . $product_details['price'] . '</p>';
        echo '<p>Sale Type: ' . $product_details['sale_type'] . '</p>';

        // Récupérer le minimum_bid de l'enchère
        $sql = "SELECT auction.minimum_bid, MAX(bid.amount) AS max_bid
          FROM item
          LEFT JOIN auction ON item.item_id = auction.item_id
          LEFT JOIN bid ON item.item_id = bid.item_id
          WHERE item.item_id = '$item_id' AND item.sale_type = 'auction'";
        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
          $minimum_bid_auction = $row['minimum_bid'];
          $maximum_bid = $row['max_bid'];
          $minimum_bid = max($minimum_bid_auction, $maximum_bid);
        } else {
          $minimum_bid = 0; // Valeur par défaut
        }

        // Formulaire pour placer une enchère
        echo '<form action="process_bid.php" method="POST">';
        echo '<input type="hidden" name="item_id" value="' . $item_id . '">';
        echo '<p>Minimum bid : ' . $minimum_bid . '</p>';
        echo 'Bid Amount: <input type="number" name="bid_amount" min="' . $minimum_bid . '" required>';
        echo '<br>';
        echo '<button type="submit" name="place_bid">Place a Bid</button>';
        echo '</form>';

        echo '<p>Sold by: <a href="seller.php?seller_id=' . $product_details['user_id'] . '">' . $product_details['seller'] . '</a></p>';

        echo '<div class="bid-history">';
        echo '<h3>Bid History</h3>';

        // Obtenir la date de fin de l'enchère
        $sql1 = "SELECT end_date FROM auction WHERE item_id = $item_id";
        $result_time = $connection->query($sql1);

        if ($result_time->num_rows > 0) {
          $row1 = $result_time->fetch_assoc();
          $timer = $row1['end_date'];
        } else {
          $timer = null;
        }

        // Compte à rebours
        echo '<h1 id="countdown"></h1>';
        echo '<script>
              var targetDate = new Date("' . $timer . '");

              function updateCountdown() {
                  // Date et heure actuelles
                  var now = new Date();

                  var diff = targetDate - now;

                  if (diff <= 0) {
                      // Arrêter le décompte et afficher "Terminé" ou une autre indication
                      document.getElementById("countdown").innerHTML = "Terminé";
                      return;
                  }

                  // Calculer les jours, heures, minutes et secondes restants
                  var days = Math.floor(diff / (1000 * 60 * 60 * 24));
                  var hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                  var minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                  var seconds = Math.floor((diff % (1000 * 60)) / 1000);

                  // Afficher le décompte
                  document.getElementById("countdown").innerHTML = days + " days, " + hours + " hours, " + minutes + " minutes, " + seconds + " seconds";

                  // Mettre à jour le décompte toutes les secondes
                  setTimeout(updateCountdown, 1000);
              }

              // Démarrer le décompte initial
              if (targetDate) {
                  updateCountdown();
              }
          </script>';

        // Obtenir les informations sur les enchères passées
        $sql_history = "SELECT user.username, bid.amount, bid.bid_date FROM bid
                          INNER JOIN user ON bid.user_id = user.user_id
                          WHERE bid.item_id = '$item_id'
                          ORDER BY bid.bid_date DESC";
        $result_history = $connection->query($sql_history);

        if ($result_history->num_rows > 0) {
          $bidHistoryData = array();
          while ($row_history = $result_history->fetch_assoc()) {
            $username = $row_history['username'];
            $amount = $row_history['amount'];
            $bid_date = $row_history['bid_date'];

            // Informations sur l'enchère passée
            $formatted_bid_date = date('M d, Y H:i:s', strtotime($bid_date));
            echo '<p><strong>' . $username . '</strong> placed a bid of $' . $amount . ' on ' . $formatted_bid_date . '</p>';

            // Ajout de l'historique des enchères
            $bidHistoryData[] = array(
              'username' => $formatted_bid_date,
              'amount' => $amount
            );
          }

          // Format JSON pour JavaScript
          $bidHistoryDataJSON = json_encode($bidHistoryData);

          // Affichage graphique
          echo '<center>';
          echo '<canvas id="bid-chart" style="display: block; box-sizing: border-box; height: 130px;"></canvas>';
          echo '</center>';

          // Script graphique
          echo '<script>
              var bidHistoryData = ' . $bidHistoryDataJSON . ';

              var usernames = bidHistoryData.map(function(item) {
                  return item.username;
              });

              var amounts = bidHistoryData.map(function(item) {
                  return item.amount;
              });

              var ctx = document.getElementById("bid-chart").getContext("2d");
              new Chart(ctx, {
                  type: "bar",
                  data: {
                      labels: usernames,
                      datasets: [{
                          label: "Bid Amount",
                          data: amounts,
                          backgroundColor: "rgba(75, 192, 192, 0.8)"
                      }]
                  },
                  options: {
                      responsive: true,
                      scales: {
                          y: {
                              beginAtZero: true,
                              ticks: {
                                  stepSize: 35 // Définissez ici le pas souhaité pour laxe y
                              }
                          }
                      },
                      plugins: {
                          legend: {
                              display: false
                          }
                      },
                      indexAxis: "x",
                      barPercentage: 0.1
                  }
              });
          </script>';

        } else {
          echo 'No bid history available.';
        }

        echo '</div>';
      }

    } else {
      echo 'Produit non trouvé.';
    }

    $connection->close();
    ?>
  </div>
</div>


    </div>
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
