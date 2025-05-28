<?php
$conn = new mysqli("db", "root", "root", "testdb");
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

$username = $_GET['username'] ?? '';
$password = $_GET['password'] ?? ''; // In produzione si usa POST
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Utenti</title>
    <title>Login Utenti</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Remix Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        :root {
            
            --primary-color: #8b5cf6;  
            --primary-color-light: #c4b5fd;  
            --primary-color-dark: #5b21b6;  

            --white-color: #ffffff;
            --black-color: #1f2937;
            --gray-color: #6b7280;
            --gray-color-light: #f3f4f6;
            --error-color: #ef4444;
            --success-color: #22c55e;

            --body-font: "Inter", sans-serif;
            --h1-font-size: 1.75rem;
            --normal-font-size: 1rem;
            --small-font-size: .875rem;
            --font-medium: 500;
            --font-semi-bold: 600;
            --border-radius: 0.75rem;
            --border-radius-sm: 0.5rem;
            --transition: all 0.3s ease;
        }

        * {
            box-sizing: border-box;
            padding: 0;
            margin: 0;
        }

        body {
            background: url('foto1.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: var(--body-font);
            font-size: var(--normal-font-size);
            color: var(--black-color);
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 1rem;
        }

        .login {
            width: 100%;
            max-width: 420px;
        }

        .login__form {
            background-color: var(--white-color);
            padding: 2.5rem;
            border-radius: var(--border-radius);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .login__title {
            color: var(--black-color);
            text-align: center;
            font-size: var(--h1-font-size);
            font-weight: var(--font-semi-bold);
            margin-bottom: 2rem;
        }

        .login__content {
            display: grid;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .login__box {
            position: relative;
        }

        .login__input {
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--gray-color-light);
            border-radius: var(--border-radius-sm);
            font-size: var(--normal-font-size);
            color: var(--black-color);
            transition: var(--transition);
        }

        .login__input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .login__label {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-color);
            pointer-events: none;
            transition: var(--transition);
        }

        .login__input:focus + .login__label,
        .login__input:not(:placeholder-shown) + .login__label {
            top: 0;
            left: 0.75rem;
            font-size: var(--small-font-size);
            background-color: var(--white-color);
            padding: 0 0.25rem;
            color: var(--primary-color);
        }

        .login__eye {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--gray-color);
            transition: var(--transition);
        }

        .login__eye:hover {
            color: var(--primary-color);
        }

        .login__button {
            width: 100%;
            padding: 1rem;
            background-color: var(--primary-color);
            color: var(--white-color);
            font-weight: var(--font-semi-bold);
            border-radius: var(--border-radius-sm);
            cursor: pointer;
            transition: var(--transition);
            border: none;
            font-size: var(--normal-font-size);
        }

        .login__button:hover {
            background-color: var(--primary-color-dark);
            transform: translateY(-2px);
        }

        .login__register {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--gray-color);
        }

        .login__register a {
            color: var(--primary-color);
            font-weight: var(--font-medium);
            text-decoration: none;
            transition: var(--transition);
        }

        .login__register a:hover {
            color: var(--primary-color-dark);
            text-decoration: underline;
        }

        .error {
            color: var(--error-color);
            background-color: #fef2f2;
            padding: 1rem;
            border-radius: var(--border-radius-sm);
            margin-bottom: 1rem;
            font-size: var(--small-font-size);
        }

        .success {
            color: var(--success-color);
            background-color: #f0fdf4;
            padding: 1rem;
            border-radius: var(--border-radius-sm);
            margin-bottom: 1rem;
            font-size: var(--small-font-size);
        }

        .results {
            margin-top: 1.5rem;
        }

        @media screen and (min-width: 576px) {
            .login__form {
                padding: 3rem;
            }
            
            .login__title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="login">
        <form class="login__form" method="get" action="">
            <h1 class="login__title">Accesso Utente</h1>
            
            <div class="login__content">
                <div class="login__box">
                    <input type="text" id="login-username" class="login__input" name="username" value="<?php echo htmlspecialchars($username); ?>" placeholder=" " required>
                    <label for="login-username" class="login__label">Nome Utente</label>
                </div>
                
                <div class="login__box">
                    <input type="password" id="login-pass" class="login__input" name="password" placeholder=" " required>
                    <label for="login-pass" class="login__label">Password</label>
                    <i class="ri-eye-off-line login__eye" id="login-eye"></i>
                </div>
            </div>

            <button type="submit" class="login__button">Accedi</button>

            <div class="login__register">
                Non hai un account? <a href="#">Registrati</a>
            </div>
        </form>

        <?php
        if (!empty($username) || !empty($password)) {
            $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
            
            if ($conn->multi_query($query)) {
                do {
                    if ($result = $conn->store_result()) {
                        echo '<div class="results">';
                        
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<div class="error" class="result-item">';
                                echo "<strong>ID:</strong> " . htmlspecialchars($row['id']) . "<br>";
                                echo "<strong>Utente:</strong> " . htmlspecialchars($row['username']) . "<br>";
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
         const showHiddenPass = (loginPass, loginEye) => {
            const input = document.getElementById(loginPass),
                  iconEye = document.getElementById(loginEye)

            iconEye.addEventListener('click', () => {
                if(input.type === 'password') {
                    input.type = 'text'
                    iconEye.classList.remove('ri-eye-off-line')
                    iconEye.classList.add('ri-eye-line')
                } else {
                    input.type = 'password'
                    iconEye.classList.remove('ri-eye-line')
                    iconEye.classList.add('ri-eye-off-line')
                }
            })
        }

        showHiddenPass('login-pass', 'login-eye')

        // Aggiungi questo codice per gestire il refresh
        window.onbeforeunload = function() {
            window.location.href = window.location.pathname;
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
