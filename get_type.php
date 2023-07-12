<?php
// Récupérer la catégorie à partir de la requête GET
$type = isset($_GET['type']) ? $_GET['type'] : '';

// Établir la connexion à la base de données
$host = 'localhost'; // Remplacez par l'adresse de votre serveur de base de données
$username = 'root'; // Remplacez par votre nom d'utilisateur de base de données
$password = 'root'; // Remplacez par votre mot de passe de base de données
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
        $itemId = $row['item_id'];
        $saleType = $row['sale_type'];

        // Vérifier si l'article est de type "auction"
        if ($saleType === 'auction') {
            // Récupérer le statut de l'enchère depuis la table "auction"
            $auctionStatusSql = "SELECT status FROM auction WHERE item_id = $itemId";
            $auctionStatusResult = mysqli_query($connection, $auctionStatusSql);

            if ($auctionStatusResult && mysqli_num_rows($auctionStatusResult) > 0) {
                $auctionStatusRow = mysqli_fetch_assoc($auctionStatusResult);
                $auctionStatus = $auctionStatusRow['status'];

                // Afficher uniquement les enchères en cours
                if ($auctionStatus === 'ongoing') {
                    // Afficher les informations du produit
                    $itemName = $row['name'];
                    $itemPrice = $row['price'];
                    $itemPhoto = $row['photo'];
                    $endDate = '';

                    // Convertir les données binaires en base64
                    $itemPhotoEncoded = base64_encode($itemPhoto);

                    echo '<div class="item">';
                    echo '<h3 class="item-name">' . $itemName . '</h3>';
                    echo '<img src="data:image/jpeg;base64,' . $itemPhotoEncoded . '" alt="Item Photo" class="item-photo">';
                    echo '<p class="item-price">' . $itemPrice . '</p>';
                    echo '<a href="items_details.php?itemId=' . $itemId . '" class="buy-button" data-item-id="' . $itemId . '">Acheter</a>';

                    // Récupérer la date de fin d'enchère de la table "auction"
                    $auctionEndDateSql = "SELECT end_date FROM auction WHERE item_id = $itemId";
                    $auctionEndDateResult = mysqli_query($connection, $auctionEndDateSql);

                    if ($auctionEndDateResult && mysqli_num_rows($auctionEndDateResult) > 0) {
                        $auctionEndDateRow = mysqli_fetch_assoc($auctionEndDateResult);
                        $endDate = $auctionEndDateRow['end_date'];

                        // Convertir la date de fin en objet DateTime
                        $endDateTime = new DateTime($endDate);

                        // Date et heure actuelles
                        $now = new DateTime();

                        // Calculer la différence entre les deux dates
                        $interval = $now->diff($endDateTime);

                        // Obtenir le temps restant sous forme de jours, heures, minutes et secondes
                        $remainingDays = $interval->format('%a');
                        $remainingHours = $interval->format('%h');
                        $remainingMinutes = $interval->format('%i');
                        $remainingSeconds = $interval->format('%s');

                        // Afficher le temps restant
                        echo '<p class="time-remaining" id="time-remaining-' . $itemId . '">';
                        echo 'Temps restant : ' . $remainingDays . ' jours, ' . $remainingHours . ' heures, ' . $remainingMinutes . ' minutes, ' . $remainingSeconds . ' secondes';
                        echo '</p>';

                        // JavaScript pour mettre à jour le temps restant à intervalles réguliers
                        echo '<script>
                            // Date/heure spécifiée (date de fin de l\'enchère)
                            var targetDate = new Date("' . $endDate . '");

                            // ID de l\'élément de temps restant correspondant à cet article
                            var timerId = "time-remaining-' . $itemId . '";

                            // Fonction pour mettre à jour le temps restant
                            function updateRemainingTime() {
                                // Date et heure actuelles
                                var now = new Date();

                                // Calculer la différence entre les deux dates en millisecondes
                                var diff = targetDate - now;

                                // Calculer les jours, heures, minutes et secondes restants
                                var days = Math.floor(diff / (1000 * 60 * 60 * 24));
                                var hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                var minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                                var seconds = Math.floor((diff % (1000 * 60)) / 1000);

                                // Construire la chaîne de temps restant
                                var remainingTimeStr = "Temps restant : " + days + " jours, " + hours + " heures, " + minutes + " minutes, " + seconds + " secondes";

                                // Mettre à jour le contenu de l\'élément de temps restant
                                document.getElementById(timerId).textContent = remainingTimeStr;
                            }

                            // Mettre à jour le temps restant initialement
                            updateRemainingTime();

                            // Mettre à jour le temps restant toutes les secondes
                            setInterval(updateRemainingTime, 1000);
                        </script>';
                    }

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
