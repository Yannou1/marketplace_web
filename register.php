<?php
session_start();
// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['username'])) {
  header('Location: profile.php');
  exit;
}

// Vérifier si le formulaire d'inscription est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Récupérer les données du formulaire
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Effectuer les vérifications nécessaires (exemple: vérification de champs vides)
  if (empty($username) || empty($email) || empty($password)) {
    $_SESSION['register_error'] = 'Veuillez remplir tous les champs.';
    header('Location: register.php');
    exit;
  }

  // Effectuer d'autres vérifications, par exemple :
  // - Vérifier si l'utilisateur existe déjà dans la base de données
  // - Vérifier la validité de l'adresse email
  // - Hasher le mot de passe avant de le stocker dans la base de données
  // ...

  // Si les vérifications sont réussies, enregistrer le nouvel utilisateur
  // Ici, vous devez ajouter le code pour enregistrer l'utilisateur dans votre base de données
  // par exemple en utilisant des requêtes SQL avec PDO ou MySQLi

  // Exemple d'utilisation de PDO pour enregistrer l'utilisateur
  // Assurez-vous de configurer correctement votre connexion à la base de données

  // Paramètres de connexion à la base de données
  $host = 'localhost'; // L'adresse du serveur de la base de données (par exemple: localhost)
$dbname = 'infinitydb'; // Le nom de votre base de données
$username1 = 'root'; // Le nom d'utilisateur de la base de données
$password1 = 'root';

  try {
    // Connexion à la base de données avec PDO
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username1, $password1);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête pour insérer l'utilisateur dans la table appropriée
    $query = "INSERT INTO User (username, email, password) 
              VALUES (:username, :email, :password)";
    $statement = $db->prepare($query);

    // Bind des valeurs des paramètres
    $statement->bindParam(':username', $username);
    $statement->bindParam(':email', $email);
    $statement->bindParam(':password', $password);

    // Exécution de la requête
    $statement->execute();

    // Rediriger l'utilisateur vers la page de connexion
    header('Location: account.php');
    exit;
  } catch (PDOException $e) {
    // Gérer les erreurs de la base de données
    $_SESSION['register_error'] = 'Erreur lors de l\'enregistrement de l\'utilisateur.';
    // Rediriger vers la page d'inscription avec le message d'erreur
    header('Location: register.php');
    exit;
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
          <a href="categories.html">Categories</a>
          <ul class="sub-menu">
            <li><a href="categories.html">All categories</a></li>
            <li><a href="#">Apple product</a></li>
            <li><a href="#">Cars</a></li>
            <li><a href="#">Moto</a></li>
          </ul>
        </li>
        <li class="menu-item buy-menu-item">
          <a href="buy.html">Buy</a>
          <ul class="sub-menu">
            <li><a href="#">All</a></li>
            <li><a href="#">Buy it now</a></li>
            <li><a href="#">Auction</a></li>
            <li><a href="#">Best offers</a></li>
          </ul>
        </li>
        <li class="menu-item"><a href="sell.html">Sell</a></li>
        <!-- Ajoutez autant de choix que nécessaire -->
      </ul>
    </div>
</div>

    <div class="navigation">
      <a href="buy.html"><img src="logo/buying.png" alt="Buying"><span>Buy</span></a>
      <a href="sell.html"><img src="logo/sell.png" alt="Sell"><span>Sell</span></a>
    </div>
    <div class="logo-site">
      <a href="index.php"><img class="site-logo" src="logo/logo2.png" alt="Logo"></a>
    </div>

    <div class="nav-user">
      <a href="cart.html">
        
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
      <div class="form-container">
  <div class="register-section">
    <h2>Register</h2>
    <?php
    if (isset($_SESSION['register_error'])) {
      echo '<p class="error-message">' . $_SESSION['register_error'] . '</p>';
      unset($_SESSION['register_error']);
    }
    ?>
    <form action="register.php" method="post">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" required>
      <label for="email">Email</label>
      <input type="email" id="email" name="email" required>
      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>
      <button type="submit">Register</button>
    </form>
  </div>
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
      <p>Téléphone: 123-456-7890</p>
    </div>
    <div class="footer-section">
      <h4>Useful links</h4>
      <ul>
        <li><a href="index.php">Accueil</a></li>
        <li><a href="categories.html">Categories</a></li>
        <li><a href="buy.html">Buy</a></li>
        <li><a href="sell.html">Sell</a></li>
        <li><a href="account.html">Account</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <p>All rights reserved &copy; 2023 - INFINITY Store</p>
  </div>
</footer>

</html>
