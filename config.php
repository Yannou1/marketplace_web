<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}


$nom = $_POST['nom'];
$email = $_POST['email'];
$password = $_POST['passwords'];

$sql = "INSERT INTO user (username, email, password) VALUES ('$nom', '$email', '$password')";

if ($conn->query($sql) === true) {
    echo "<script>alert('Enregistrement réussi'); window.location.href = 'account.html';</script>";
    exit();
}
 else {
    echo "<script>alert('Erreur lors de l\'enregistrement : " . $conn->error . "');</script>";
}

$conn->close();
?>
