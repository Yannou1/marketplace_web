<?php
// process_order.php
session_start();

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "infinitydb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Vérifier si le formulaire a été soumis et si l'utilisateur est connecté
if (isset($_POST['buy']) && isset($_SESSION['user_id'])) {
    // Récupérer les valeurs du formulaire
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];

    // Récupérer l'ID de l'utilisateur
    $user_id = $_SESSION['user_id'];

    // Insérer les informations dans la table "cart"
    $sql = "INSERT INTO cart (user_id, item_id, quantity) VALUES ($user_id, $item_id, $quantity)";
    if ($conn->query($sql) === TRUE) {
        echo "Item added to cart successfully.";
    } else {
        echo "Error adding item to cart: " . $conn->error;
    }
}

$conn->close();
// Rediriger vers la page cart.php
header("Location: cart.php");
exit;
?>
