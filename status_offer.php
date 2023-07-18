<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "infinitydb";

$connection = new mysqli($servername, $username, $password, $dbname);
if ($connection->connect_error) {
    die("Connexion échouée : " . $connection->connect_error);
}

if (isset($_POST['accept_offer'])) {
    $offerId = $_POST['offer_id'];

    $updateQuery = "UPDATE direct_offers SET status = 'accepted' WHERE offer_id = $offerId";
    $updateResult = mysqli_query($connection, $updateQuery);

    if (!$updateResult) {
        die('Error: ' . mysqli_error($connection));
    }

    // Rediriger vers la page des offres acceptées
    header("Location: http://website/profile.php?page=offer");
    exit;
}

if (isset($_POST['reject_offer'])) {
    $offerId = $_POST['offer_id'];

    $updateQuery = "UPDATE direct_offers SET status = 'rejected' WHERE offer_id = $offerId";
    $updateResult = mysqli_query($connection, $updateQuery);

    if (!$updateResult) {
        die('Error: ' . mysqli_error($connection));
    }

    // Rediriger vers la page des offres refusées
    header("Location: http://website/profile.php?page=offer");
    exit;
}

if (isset($_POST['counter_offer'])) {
    $offerId = $_POST['offer_id'];
    $oldOfferQuery = "SELECT * FROM direct_offers WHERE offer_id = $offerId";
    $oldOfferResult = mysqli_query($connection, $oldOfferQuery);

    if (!$oldOfferResult) {
        die('Error: ' . mysqli_error($connection));
    }

    $oldOfferData = mysqli_fetch_assoc($oldOfferResult);

    // Inverser les rôles de l'émetteur et du destinataire
    $sender = $oldOfferData['receiver'];
    $receiver = $oldOfferData['sender'];

    // Obtenir le nouveau prix de l'offre
    $counterOfferPrice = $_POST['counter_offer_amount'];

    // Insérer la nouvelle offre
    $insertQuery = "INSERT INTO direct_offers (item_id, user_id, price, timestamp, status, seller_id, sender, receiver)
                    VALUES ('" . $oldOfferData['item_id'] . "', '" . $oldOfferData['user_id'] . "', '$counterOfferPrice', NOW(), 'counter-offer', '" . $oldOfferData['seller_id'] . "', '$sender', '$receiver')";
    $insertResult = mysqli_query($connection, $insertQuery);

    if (!$insertResult) {
        die('Error: ' . mysqli_error($connection));
    }

    // Mettre à jour le statut de l'offre d'origine en "counter-offer"
    $updateOriginalQuery = "UPDATE direct_offers SET status = 'counter-offer' WHERE offer_id = $offerId";
    $updateOriginalResult = mysqli_query($connection, $updateOriginalQuery);

    if (!$updateOriginalResult) {
        die('Error: ' . mysqli_error($connection));
    }

    // Rediriger vers la page des offres en attente
    header("Location: http://website/profile.php?page=offer");
    exit;
}
?>
