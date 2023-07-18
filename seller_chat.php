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

$itemId = $_GET['item_id'];

// Récupérer les informations de l'article associé à l'offre directe
$itemQuery = "SELECT * FROM item WHERE item_id = $itemId";
$itemResult = mysqli_query($connection, $itemQuery);

if ($itemResult) {
    $itemDetails = mysqli_fetch_assoc($itemResult);
} else {
    die("Une erreur s'est produite lors de la récupération des détails de l'article.");
}

// Récupérer les messages de la conversation entre l'utilisateur et le vendeur
$conversationQuery = "SELECT * FROM direct_messages WHERE item_id = $itemId";
$conversationResult = mysqli_query($connection, $conversationQuery);

if ($conversationResult) {
    // Vérifier si des messages sont présents dans la conversation
    if (mysqli_num_rows($conversationResult) > 0) {
        // Afficher les messages de la conversation
        while ($message = mysqli_fetch_assoc($conversationResult)) {
            $sender = ($message['user_id'] == $_SESSION['user_id']) ? 'Vous' : 'Vendeur';
            echo '<div class="message-container">';
            echo '<span class="sender">' . $sender . '</span>: ';
            echo '<span class="message">' . $message['message'] . '</span>';
            echo '<span class="timestamp">' . $message['timestamp'] . '</span>';
            echo '</div>';
        }
    } else {
        echo 'Aucun message trouvé.';
    }
} else {
    die("Une erreur s'est produite lors de la récupération des messages de la conversation.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chat avec le vendeur</title>
    <style>
        .message-container {
            margin-bottom: 10px;
        }
        .sender {
            font-weight: bold;
        }
        .timestamp {
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>
    <h1>Chat avec le vendeur</h1>
    <h2>Article: <?php echo $itemDetails['name']; ?></h2>

    <div id="chat-container">
    </div>

    <form id="message-form">
        <input type="hidden" name="item_id" value="<?php echo $itemId; ?>">
        <textarea name="message" placeholder="Votre message"></textarea>
        <button type="submit">Envoyer</button>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Soumettre le formulaire de message
            $('#message-form').submit(function(e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: 'submit_message.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#chat-container').append(response);
                        $('#message-form textarea').val('');
                    }
                });
            });
        });
    </script>
</body>
</html>
