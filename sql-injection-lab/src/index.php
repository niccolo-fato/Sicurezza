<?php
$conn = new mysqli("db", "root", "root", "testdb");
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

$user = $_GET['user'] ?? '';

// Vulnerabilità: input non filtrato direttamente nella query
$query = "SELECT * FROM users WHERE username = '$user'";

// Esegui query multipla per piggybacking (se ci sono più query separate da ;)
if ($conn->multi_query($query)) {
    do {
        if ($result = $conn->store_result()) {
            echo "<h3>Risultati query:</h3>";
            while ($row = $result->fetch_assoc()) {
                echo "User: " . htmlspecialchars($row['username']) . "<br>";
            }
            $result->free();
        } else {
            // Se non ci sono risultati (es. dopo DROP TABLE)
            if ($conn->errno) {
                echo "Errore: " . $conn->error . "<br>";
            } else {
                echo "Query eseguita con successo.<br>";
            }
        }
    } while ($conn->more_results() && $conn->next_result());
} else {
    echo "Errore nella query: " . $conn->error;
}

$conn->close();
?>
