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

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM fact WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<div class='container mt-5'>";
        echo "<h2>Facture #" . $row["id"] . "</h2>";
        echo "<p>Client: " . $row["client"] . "</p>";
        echo "<p>Montant: " . $row["montat"] . "</p>";
        echo "<button onclick='window.print()' class='btn btn-secondary'>Imprimer</button>";
        echo "</div>";
    } else {
        echo "Facture non trouvée";
    }
}

$conn->close();
?>
