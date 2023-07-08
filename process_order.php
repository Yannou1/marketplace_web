<?php
// process_order.php
session_start();
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "infinitydb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Vérifier si le formulaire a été soumis et si l'utilisateur est connecté
if (isset($_POST['buy']) && isset($_SESSION['username'])) {
    // Récupérer les valeurs du formulaire
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];

    // Vérifier si l'article est disponible en stock
    $sql = "SELECT stock FROM Item WHERE item_id = $item_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stock = $row['stock'];

        if ($quantity <= $stock) {
            // Insérer la commande dans la table "orders"
            $user_id = $_SESSION['user_id'];
            $price = $product_details['price'] * $quantity;
            $date = date("Y-m-d H:i:s");

            $sql = "INSERT INTO Orders (user_id, item_id, quantity, purchase_date) VALUES ('$user_id', '$item_id', '$quantity', '$date')";
            if ($conn->query($sql) === TRUE) {
                echo "Order placed successfully.";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Insufficient stock.";
        }
    } else {
        echo "Item not found.";
    }
}

$conn->close();
// Rediriger vers la page cart.php
header("Location: cart.php");
exit;
?>
