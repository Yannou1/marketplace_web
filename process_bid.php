<?php
include 'session.php';
include 'db_connect.php';

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
    $sql1 = "UPDATE item SET price = '$bid_amount' WHERE item_id = '$item_id'";
    if (($connection->query($sql) === TRUE) && ($connection->query($sql1) === TRUE)) {
        echo "Item added to bid successfully.";
    } else {
        echo "Error adding item to bid: " . $connection->error;
    }
}

$connection->close();

// Rediriger vers la page cart.php
header("Location: cart.php");
exit;
?>
