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

$sql = "SELECT * FROM fact";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<div class='container mt-5'>";
    echo "<h2>Liste des Factures</h2>";
    echo "<table class='table table-bordered'>";
    echo "<thead><tr><th>ID</th><th>Client</th><th>Montant</th><th>Action</th></tr></thead>";
    echo "<tbody>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["id"] . "</td><td>" . $row["client"] . "</td><td>" . $row["montant"] . "</td>";
        echo "<td><a href='print_invoice.php?id=" . $row["id"] . "' class='btn btn-secondary'>Imprimer</a></td></tr>";
    }
    echo "</tbody></table></div>";
} else {
    echo "0 résultats";
}

$conn->close();
?>
