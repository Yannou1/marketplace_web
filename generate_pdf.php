<?php
// Inclure la bibliothèque TCPDF
require_once('biblio/tcpdf/tcpdf.php');

// Récupérer l'ID de commande à partir du paramètre GET
$orderId = $_GET['order_id'];

// Établir la connexion à la base de données
$host = 'localhost';
$username = 'root';
$password = 'root';
$database = 'infinitydb';

$connection = mysqli_connect($host, $username, $password, $database);

if (!$connection) {
  die('Erreur de connexion à la base de données : ' . mysqli_connect_error());
}

// Récupérer les informations de la commande à partir de la base de données
$orderQuery = "SELECT * FROM orders WHERE order_id = $orderId";
$orderResult = mysqli_query($connection, $orderQuery);
$order = mysqli_fetch_assoc($orderResult);

// Vérifier si la commande existe
if (!$order) {
  die('Commande non trouvée.');
}

// Récupérer les informations de l'utilisateur à partir de la base de données
$userId = $order['user_id'];
$userQuery = "SELECT * FROM User WHERE user_id = $userId";
$userResult = mysqli_query($connection, $userQuery);
$user = mysqli_fetch_assoc($userResult);

// Récupérer les articles de la commande à partir de la base de données
$cartQuery = "SELECT * FROM cart WHERE order_id = $orderId";
$cartResult = mysqli_query($connection, $cartQuery);
$cartItems = mysqli_fetch_all($cartResult, MYSQLI_ASSOC);

// Créer un nouvel objet TCPDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8');

// Ajouter une page
$pdf->AddPage();

// Ajouter le logo ou l'en-tête de votre entreprise
$pdf->Image('logo/logo2.png', 10, 10, 30, '', 'PNG');

// Ajouter les informations de l'utilisateur
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Informations utilisateur', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 7, 'Username: ' . $user['username'], 0, 1, 'L');
$pdf->Cell(0, 7, 'Nom: ' . $user['last_name'], 0, 1, 'L');
$pdf->Cell(0, 7, 'Prénom: ' . $user['first_name'], 0, 1, 'L');
$pdf->Cell(0, 7, 'Email: ' . $user['email'], 0, 1, 'L');
$pdf->Ln(10);

// Ajouter les informations de la commande
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Informations de commande', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 7, 'Numéro de commande: ' . $order['order_id'], 0, 1, 'L');
$pdf->Cell(0, 7, 'Moyen de paiement: ' . $order['payment_informations'], 0, 1, 'L');
$pdf->Ln(10);

// Ajouter les articles de la commande
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Articles de commande', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->SetFillColor(220, 220, 220);
$pdf->Cell(60, 7, 'Nom', 1, 0, 'C', 1);
$pdf->Cell(30, 7, 'Quantité', 1, 0, 'C', 1);
$pdf->Cell(40, 7, 'Prix unitaire', 1, 0, 'C', 1);
$pdf->Ln();

foreach ($cartItems as $item) {
  $itemId = $item['item_id'];
  $itemQuery = "SELECT * FROM Item WHERE item_id = $itemId";
  $itemResult = mysqli_query($connection, $itemQuery);
  $itemData = mysqli_fetch_assoc($itemResult);

  $pdf->Cell(60, 7, $itemData['name'], 1, 0, 'L');
  $pdf->Cell(30, 7, $item['quantity'], 1, 0, 'C');
  $pdf->Cell(40, 7, $itemData['price'], 1, 0, 'R');
  $pdf->Ln();
}

$pdf->Ln(10);

// Ajouter l'adresse de livraison
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Adresse de livraison', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(0, 7, $order['shipping_address'], 0, 'L');

// Générer le contenu du PDF
$content = $pdf->Output('', 'S');

// Définir les en-têtes pour le téléchargement du fichier PDF
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="invoice.pdf"');

// Envoyer le contenu du PDF au navigateur
echo $content;

// Fermer la connexion à la base de données
mysqli_close($connection);

?>
