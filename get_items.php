<?php
// Récupérer la catégorie à partir de la requête GET
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Établir la connexion à la base de données
include 'db_connect.php';

// Construire la requête SQL pour récupérer les produits correspondants de la table Item
$sql = "SELECT * FROM Item";

// Vérifier si une catégorie a été spécifiée dans la requête GET
if (!empty($category) && $category != 'all') {
    // Récupérer l'ID de catégorie correspondant à partir de la table Category
    $category = mysqli_real_escape_string($connection, $category);
    $sql = "SELECT * FROM Item WHERE category_id IN (SELECT category_id FROM Category WHERE name = '$category')";
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
