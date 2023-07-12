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
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script> 
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
          $sql = "SELECT Item.*, User.username FROM Item INNER JOIN User ON Item.user_id = User.user_id WHERE Item.item_id = $item_id";
          $result = $conn->query($sql);

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

    if($product_details['sale_type'] == 'buy_now')
    {
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
        //Pour les auctions
        if ($product_details['sale_type'] == 'auction') {
          // Afficher les informations du produit
          echo '<img src="data:image/jpeg;base64,' . $product_details['photo'] . '" alt="Product Image">';
          echo '<h2>' . $product_details['name'] . '</h2>';
          echo '<p>' . $product_details['description'] . '</p>';
          echo '<p>Price: $' . $product_details['price'] . '</p>';
          echo '<p>Sale Type: ' . $product_details['sale_type'] . '</p>';
            //  $minimum_bid 
          $sql = "SELECT auction.minimum_bid, MAX(bid.amount) AS max_bid
          FROM item
          LEFT JOIN auction ON item.item_id = auction.item_id
          LEFT JOIN bid ON item.item_id = bid.item_id
          WHERE item.item_id = '$item_id' AND item.sale_type = 'auction'";
  $result = $conn->query($sql);
  
  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $minimum_bid_auction = $row['minimum_bid'];
      $maximum_bid = $row['max_bid'];
      $minimum_bid = max($minimum_bid_auction, $maximum_bid);
  } else {
      $minimum_bid = 0; // default value
  }
         // form to place a bid
          echo '<form action="process_bid.php" method="POST">';
          echo '<input type="hidden" name="item_id" value="' . $item_id . '">';
          echo '<p> Minimum bid : '.$minimum_bid .'</p>';
          echo 'Bid Amount: <input type="number" name="bid_amount" min="' . $minimum_bid . '" required>';
          echo '<br>';
          echo '<button type="submit" name="place_bid">Place a Bid</button>';
          echo '</form>';
      
          echo '<p>Sold by: <a href="seller.php?seller_id=' . $product_details['user_id'] . '">' . $product_details['seller'] . '</a></p>';

          echo '<div class="bid-history">';
          echo '<h3>Bid History</h3>';
          //get end hours
          $sql1 = "SELECT end_date FROM auction WHERE item_id = $item_id";
          $result_time = $conn->query($sql1);
          
          if ($result_time->num_rows > 0) {
              $row1 = $result_time->fetch_assoc();
              $timer = $row1['end_date'];
          } else {
              $timer = null;
          }
          
          // Décompte
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
          //getinfo past bid
          $sql_history = "SELECT user.username, bid.amount, bid.bid_date FROM bid
                          INNER JOIN user ON bid.user_id = user.user_id
                          WHERE bid.item_id = '$item_id'
                          ORDER BY bid.bid_date DESC";
          $result_history = $conn->query($sql_history);
          
          if ($result_history->num_rows > 0) {
              $bidHistoryData = array();
              while ($row_history = $result_history->fetch_assoc()) {
                  $username = $row_history['username'];
                  $amount = $row_history['amount'];
                  $bid_date = $row_history['bid_date'];
          
                  // Info of past bid
                  $formatted_bid_date = date('M d, Y H:i:s', strtotime($bid_date));
                  echo '<p><strong>' . $username . '</strong> placed a bid of $' . $amount . ' on ' . $formatted_bid_date . '</p>';
          
                  // historic adding
                  $bidHistoryData[] = array(
                      'username' => $formatted_bid_date,
                      'amount' => $amount
                  );
              }
          
              // Format JSON for JavaScript
              $bidHistoryDataJSON = json_encode($bidHistoryData);
              // graphic print
              echo '<center>';
              echo '<canvas id="bid-chart" style="display: block; box-sizing: border-box; height: 130px;"></canvas>';
              echo '</center>';
// graphical script
         
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
