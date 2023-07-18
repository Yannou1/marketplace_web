<?php
include 'db_connect.php';

// Vérifier si l'ID de la commande est passé en tant que paramètre
if (isset($_GET['order_id'])) {
    $orderId = $_GET['order_id'];

    // Supprimer la commande de la base de données
    $sql = "DELETE FROM orders WHERE order_id = $orderId";
    $result = mysqli_query($connection, $sql);

    if ($result) {
        echo 'La commande a été supprimée avec succès.';
    } else {
        echo 'Erreur lors de la suppression de la commande : ' . mysqli_error($connection);
    }
} else {
    echo 'ID de commande non spécifié.';
}

// Fermer la connexion à la base de données
mysqli_close($connection);

// Rediriger vers la page cart.php
header("Location: cart.php");
exit;
?>
