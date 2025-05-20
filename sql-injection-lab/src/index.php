<?php
$conn = new mysqli("db", "root", "root", "testdb");
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

$username = $_GET['username'] ?? '';
$password = $_GET['password'] ?? ''; // Nota: in produzione usa POST per le password
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Utenti</title>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --success-color: #27ae60;
            --error-color: #e74c3c;
            --light-gray: #f5f5f5;
            --medium-gray: #ddd;
            --dark-gray: #333;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--dark-gray);
            background-color: var(--light-gray);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        
        .container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 500px;
        }
        
        h1 {
            color: var(--secondary-color);
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
        }
        
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        label {
            font-weight: 600;
            color: var(--secondary-color);
        }
        
        input {
            padding: 12px 15px;
            border: 1px solid var(--medium-gray);
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        
        button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }
        
        button:hover {
            background-color: #2980b9;
        }
        
        .results {
            margin-top: 30px;
            border-top: 1px solid var(--medium-gray);
            padding-top: 20px;
        }
        
        .result-item {
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 6px;
            margin-bottom: 10px;
        }
        
        .error {
            color: var(--error-color);
            background-color: #fadbd8;
            padding: 12px;
            border-radius: 6px;
            margin: 15px 0;
        }
        
        .success {
            color: var(--success-color);
            background-color: #d5f5e3;
            padding: 12px;
            border-radius: 6px;
            margin: 15px 0;
        }
        
        .password-field {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--secondary-color);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Accesso Utente</h1>
        
        <form class="login-form" method="get" action="">
            <div class="form-group">
                <label for="username">Nome Utente:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <div class="password-field">
                    <input type="password" id="password" name="password" required>
                    <span class="password-toggle" onclick="togglePassword()">👁️</span>
                </div>
            </div>
            
            <button type="submit">Accedi</button>
        </form>

        <?php
        if (!empty($username) || !empty($password)) {
            $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
            
            if ($conn->multi_query($query)) {
                do {
                    if ($result = $conn->store_result()) {
                        echo '<div class="results">';
                        echo '<h3>Risultati:</h3>';
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<div class="result-item">';
                                echo "<strong>ID:</strong> " . htmlspecialchars($row['id']) . "<br>";
                                echo "<strong>Utente:</strong> " . htmlspecialchars($row['username']) . "<br>";
                                echo "<strong>Email:</strong> " . htmlspecialchars($row['email']) . "<br>";
                                echo '</div>';
                            }
                        } else {
                            echo '<div class="error">Credenziali non valide</div>';
                        }
                        $result->free();
                        echo '</div>';
                    } else {
                        if ($conn->errno) {
                            echo '<div class="error">Errore: ' . $conn->error . '</div>';
                        } else {
                            echo '<div class="success">Operazione completata</div>';
                        }
                    }
                } while ($conn->more_results() && $conn->next_result());
            } else {
                echo '<div class="error">Errore nella query: ' . $conn->error . '</div>';
            }
        }
        ?>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
            } else {
                passwordField.type = 'password';
            }
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
