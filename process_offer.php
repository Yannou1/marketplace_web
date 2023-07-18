<?php
include 'session.php';
include 'db_connect.php';

$itemId = $_POST['item_id'];
$userId = $_SESSION['user_id'];
$price = $_POST['offer'];

$sql = "SELECT Item.*, User.username FROM Item INNER JOIN User ON Item.user_id = User.user_id WHERE Item.item_id = $itemId";
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
        'seller_id' => $row['user_id'],
        'sale_type' => $row['sale_type']
    );
}

// Insérer les détails de l'offre directe dans la table des offres
$query = "INSERT INTO direct_offers (item_id, user_id, price, seller_id, sender, receiver) VALUES ($itemId, $userId, $price, '$product_details[seller_id]', $userId, '$product_details[seller_id]')";
$result = mysqli_query($connection, $query);

if ($result) {
    // Succès de l'insertion, rediriger l'utilisateur vers la page de chat avec le vendeur
    header("Location: profile.php");
    exit;
} else {
    // Gestion de l'erreur lors de l'insertion
    echo "Une erreur s'est produite lors de la soumission de l'offre directe.";
}

?>
