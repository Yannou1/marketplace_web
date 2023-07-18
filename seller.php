<?php
include 'session.php';
include 'db_connect.php';

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

// Vérifier si l'utilisateur est connecté en tant que vendeur
$isSeller = (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $sellerId);

// Traitement de la modification de la photo de profil
if ($isSeller && isset($_POST['submit'])) {
    // Vérifier si une nouvelle photo de profil a été sélectionnée
    if (isset($_FILES['profile_photo'])) {
        $file = $_FILES['profile_photo'];

        // Vérifier si le fichier est une image
        $fileType = exif_imagetype($file['tmp_name']);
        if ($fileType !== IMAGETYPE_JPEG && $fileType !== IMAGETYPE_PNG) {
            die('Seules les images au format JPEG et PNG sont autorisées.');
        }

        // Récupérer le contenu de la photo de profil
        $profilePhoto = file_get_contents($file['tmp_name']);

        // Mettre à jour la photo de profil dans la base de données
        $updateQuery = "UPDATE User SET profile_photo = ? WHERE user_id = ?";
        $statement = mysqli_prepare($connection, $updateQuery);
        mysqli_stmt_bind_param($statement, 'si', $profilePhoto, $sellerId);
        $updateResult = mysqli_stmt_execute($statement);

        if ($updateResult) {
            echo 'La photo de profil a été mise à jour avec succès.';
            // Actualiser la page pour afficher la nouvelle photo de profil
            header("Refresh:0");
        } else {
            echo 'Erreur lors de la mise à jour de la photo de profil : ' . mysqli_error($connection);
        }
    }
}
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
    <div id="page-container">
        <div class="container">
            <div class="seller">
                <h2>Seller: <?php echo $seller['username']; ?></h2>

                <?php
                // Afficher la photo de profil
                if (!empty($seller['profile_photo'])) {
                    echo '<img class="profile-photo" src="data:image/jpeg;base64,' . base64_encode($seller['profile_photo']) . '" alt="Profile Photo">';
                } else {
                    echo '<img class="profile-photo" src="default_profile_photo.jpg" alt="Default Profile Photo">';
                }
                ?>
                <?php
                // Afficher le formulaire de modification de la photo de profil pour le vendeur
                if ($isSeller) {
                    echo '<form action="" method="POST" enctype="multipart/form-data">';
                    echo '<label for="profile_photo">Change profile picture :</label>';
                    echo '<input type="file" name="profile_photo" id="profile_photo">';
                    echo '<input type="submit" name="submit" value="Edit">';
                    echo '</form>';
                }
                ?>

                <h3>Seller's products:</h3>

                <table class="seller-table">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Afficher les informations de chaque produit
                        // Récupérer les informations des produits associés au vendeur
$itemsQuery = "SELECT * FROM Item WHERE user_id = $sellerId";
$itemsResult = mysqli_query($connection, $itemsQuery);

// Vérifier s'il y a des produits associés au vendeur
if (mysqli_num_rows($itemsResult) > 0) {
    // Afficher les informations de chaque produit
    while ($row = mysqli_fetch_assoc($itemsResult)) {
        echo '<tr>';
        echo '<td><img src="data:image/jpeg;base64,' . base64_encode($row['photo']) . '" alt="Product Image"></td>';
        echo '<td>' . $row['name'] . '</td>';
        echo '<td>' . $row['description'] . '</td>';
        echo '<td>$' . $row['price'] . '</td>';
        echo '<td>' . $row['sale_type'] . '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="4">No products found.</td></tr>';
}

                        ?>
                    </tbody>
                </table>

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
