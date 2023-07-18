<?php
// Récupérer la catégorie à partir de la requête GET
$type = isset($_GET['type']) ? $_GET['type'] : '';

include 'db_connect.php';

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
        $itemId = $row['item_id'];
        $saleType = $row['sale_type'];

        // Vérifier si l'article est de type "auction"
        if ($saleType === 'auction') {
            // Récupérer le statut de l'enchère depuis la table "auction"
            $auctionStatusSql = "SELECT status, end_date FROM auction WHERE item_id = $itemId";
            $auctionStatusResult = mysqli_query($connection, $auctionStatusSql);

            if ($auctionStatusResult && mysqli_num_rows($auctionStatusResult) > 0) {
                $auctionStatusRow = mysqli_fetch_assoc($auctionStatusResult);
                $auctionStatus = $auctionStatusRow['status'];
                $endDate = $auctionStatusRow['end_date'];

                // Afficher uniquement les enchères en cours
                if ($auctionStatus === 'ongoing') {
                    // Afficher les informations du produit
                    $itemName = $row['name'];
                    $itemPrice = $row['price'];
                    $itemPhoto = $row['photo'];

                    // Convertir les données binaires en base64
                    $itemPhotoEncoded = base64_encode($itemPhoto);

                    echo '<div class="item">';
                    echo '<h3 class="item-name">' . $itemName . '</h3>';
                    echo '<img src="data:image/jpeg;base64,' . $itemPhotoEncoded . '" alt="Item Photo" class="item-photo">';
                    echo '<p class="item-price">' . $itemPrice . '</p>';
                    echo '<a href="items_details.php?itemId=' . $itemId . '" class="buy-button" data-item-id="' . $itemId . '">Acheter</a>';
                    echo '<p class="item-time-remaining" data-end-date="' . $endDate . '"></p>';
                    echo '</div>';
                }
            }
        } else {
            // Afficher les informations du produit pour les autres types de vente (non-enchère)
            $itemName = $row['name'];
            $itemPrice = $row['price'];
            $itemPhoto = $row['photo'];

            // Convertir les données binaires en base64
            $itemPhotoEncoded = base64_encode($itemPhoto);

            echo '<div class="item">';
            echo '<h3 class="item-name">' . $itemName . '</h3>';
            echo '<img src="data:image/jpeg;base64,' . $itemPhotoEncoded . '" alt="Item Photo" class="item-photo">';
            echo '<p class="item-price">' . $itemPrice . '</p>';
            echo '<a href="items_details.php?itemId=' . $itemId . '" class="buy-button" data-item-id="' . $itemId . '">Acheter</a>';
            echo '</div>';
        }
    }
} else {
    // Aucun produit trouvé dans cette catégorie ou aucun résultat pour la catégorie "all"
    echo 'No products found.';
}

// Fermer la connexion à la base de données
mysqli_close($connection);
?>

<!-- Assurez-vous d'inclure la bibliothèque Moment.js dans votre page -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<script>
function updateRemainingTime() {
    var itemTimeElements = document.getElementsByClassName('item-time-remaining');

    for (var i = 0; i < itemTimeElements.length; i++) {
        var itemTimeElement = itemTimeElements[i];
        var endDate = itemTimeElement.getAttribute('data-end-date');

        var now = moment();
        var end = moment(endDate);
        var duration = moment.duration(end.diff(now));

        var days = Math.floor(duration.asDays());
        var hours = duration.hours();
        var minutes = duration.minutes();
        var seconds = duration.seconds();

        var remainingTime = '';

        if (days > 0) {
            remainingTime += days + 'J ';
        }

        remainingTime += hours + 'H ' + minutes + 'M ' + seconds + 'S';

        itemTimeElement.innerHTML = ' <br> Temps restant :<br>' + remainingTime;
    }
}

updateRemainingTime();
setInterval(updateRemainingTime, 1000);
</script>

