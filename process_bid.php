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

if (isset($_POST['place_bid']) && isset($_SESSION['user_id'])) {
    // Récupérer les valeurs du formulaire
    $item_id = $_POST['item_id'];
    $user_id = $_POST['user_id'];
    $bid_amount = $_POST['bid_amount'];
    $bid_date = date('Y-m-d H:i:s');


    // Récupérer l'ID de l'utilisateur
    $user_id = $_SESSION['user_id'];

    // Afficher une alerte JavaScript avec les valeurs des variables
    echo "<script>alert('item_id: $item_id, bid_amount: $bid_amount, user_id: $user_id');</script>";

    // Insérer les informations dans la table "bid"
    $sql = "INSERT INTO bid (user_id, item_id, amount, bid_date) VALUES ('$user_id', '$item_id', '$bid_amount', '$bid_date')";
    if ($conn->query($sql) === TRUE) {
        echo "Item added to bid successfully.";
    } else {
        echo "Error adding item to bid: " . $conn->error;
    }
}

$conn->close();

// Rediriger vers la page cart.php
header("Location: cart.php");
exit;
?>
