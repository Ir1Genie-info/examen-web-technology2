<?php
$servername = "localhost";
$username = "muyisa";
$password = "";
$dbname = "pharmacie";

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $client = $_POST['client'];
    $amount = $_POST['amount'];

    $sql = "INSERT INTO fact (client, montat) VALUES ('$client', '$amount')";

    if ($conn->query($sql) === TRUE) {
        echo "Nouvelle facture insérée avec succès";
    } else {
        echo "Erreur: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
