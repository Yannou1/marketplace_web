<?php
// Récupérer la catégorie à partir de la requête GET
$type = isset($_GET['type']) ? $_GET['type'] : '';

// Établir la connexion à la base de données
$host = 'localhost'; // Remplacez par l'adresse de votre serveur de base de données
$username = 'root'; // Remplacez par votre nom d'utilisateur de base de données
$password = ''; // Remplacez par votre mot de passe de base de données
$database = 'infinitydb'; // Remplacez par le nom de votre base de données

$connection = mysqli_connect($host, $username, $password, $database);

if (!$connection) {
    die('Erreur de connexion à la base de données : ' . mysqli_connect_error());
}

// Construire la requête SQL pour récupérer les produits correspondants de la table Item
$sql = "SELECT * FROM Item";

// Vérifier si une catégorie a été spécifiée dans la requête GET
if (!empty($type) && $type != 'all') {
    // Récupérer les éléments correspondants au type spécifié
    $itemType = mysqli_real_escape_string($connection, $type);
    $sql = "SELECT * FROM Item WHERE sale_type = '$itemType'";
}


$result = mysqli_query($connection, $sql);

// Vérifier s'il y a des résultats
if (mysqli_num_rows($result) > 0) {
    // Parcourir les résultats et afficher les produits
    while ($row = $result->fetch_assoc()) {
        $itemName = $row['name'];
        $itemPrice = $row['price'];
        $itemPhoto = $row['photo'];
        $itemId = $row['item_id'];

        // Convertir les données binaires en base64
        $itemPhotoEncoded = base64_encode($itemPhoto);

        echo '<div class="item">';
        echo '<h3 class="item-name">' . $itemName . '</h3>';
        echo '<img src="data:image/jpeg;base64,' . $itemPhotoEncoded . '" alt="Item Photo" class="item-photo">';
        echo '<p class="item-price">' . $itemPrice . '</p>';
        echo '<a href="items_details.php?itemId=' . $itemId . '" class="buy-button" data-item-id="' . $itemId . '">Acheter</a>';
        echo '</div>';
    }
} else {
    // Aucun produit trouvé dans cette catégorie ou aucun résultat pour la catégorie "all"
    echo 'No products found.';
}

// Fermer la connexion à la base de données
mysqli_close($connection);
?>
