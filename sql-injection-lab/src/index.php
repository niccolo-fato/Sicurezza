


<?php
$conn = new mysqli("db", "root", "root", "testdb");
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

$username = $_GET['username'] ?? '';
$password = $_GET['password'] ?? '';
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Remix Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet"><s></s>
  <title>Login</title>
  <style>
    :root {
  --gray-color-light: #d1d5db;
  --gray-color: #6b7280;
  --black-color: #111827;
  --white-color: #ffffff;
  --primary-color: #4f46e5;
  --normal-font-size: 1rem;
  --small-font-size: 0.75rem;
  --border-radius-sm: 0.5rem;
  --transition: 0.3s ease;
}
    * {
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }
    body {
      margin: 0;
      background-color: #f3f1e8;
    }
    .container {
      display: flex;
      height: 100vh;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }
    .login-box {
      display: flex;
      max-width: 900px;
      width: 100%;
      background: white;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      
    }
    .form-side {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    .form-side h2 {
      margin-bottom: 20px;
      font-size: 2.5rem;
      text-align: center;
    }
    .form-side input {
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 8px;
      width: 100%;
    }
    .form-side button {
      padding: 12px;
      background-color: #2d3e32;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      max-width: 460px;
      margin: 2rem auto;
      display: grid;
      gap: 1.5rem;
      width: 90%;
      padding: 1rem;
      
    }
    a {
  color: inherit;           /* usa il colore del genitore */
  text-decoration: none;    /* rimuove la sottolineatura */
}
    .form-side .links {
      font-size: 14px;
      color: #555;
      text-align: center;
      font-size: 14px;
      color: #555;
      margin-top: -1.7rem;
    }
    .form-side .socials {
      margin-top: 10px;
    }
    .form-side .socials img {
      width: 24px;
      cursor: pointer;
    }
    .image-side {
      flex-basis: 50%;
       background-color: #f3f1e8;
    display: flex;
  align-items: center;
  justify-content: center;
  padding-right: 50px;
    }
    .image-side svg {
      max-width: 100%;
      height: auto;
    }
    .login__content {
    max-width: 480px;
    margin: 2rem auto;
    display: grid;
    gap: 1.5rem;
    width: 90%;
    padding: 1rem;
        margin-bottom: 0;
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
        border-color: rgb(255 199 40);
        box-shadow: 0 0 0 4px rgba(229, 165, 70, 0.1);
        outline: none;
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
        color: rgb(253, 162, 25);
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
    .image-side svg, 
    .image-side embed {
    width: 100%;
    max-width: 1000px;
    height: auto;
    margin-left: -150px;
    }
    .form-side .socials {
  margin-top: 2rem;
  text-align: center;
}

.social-title {
  display: block;
  margin-bottom: 0.75rem;
  font-size: 0.95rem;
  color: #444;
  
}

.social-icons {
  display: flex;
  justify-content: center;
  gap: 1.2rem;
  
}

.social-icons img {
  width: 28px;
  height: 28px;
  cursor: pointer;
  transition: transform 0.2s;
  width: 30px;
  height: 30px;
  margin: 0 8px;
  object-fit: contain;
  filter: brightness(0); 
}

.social-icons img:hover {
  transform: scale(1.1);
}
.login__button:hover {
            background-color: rgb(255 199 40);
            transform: translateY(-2px);
 }
.error {
            color: var(--error-color);
            background-color: #fef2f2;
            padding: 1rem;
            border-radius: var(--border-radius-sm);
            margin-bottom: 1rem;
            font-size: var(--small-font-size);
        }



  </style>
</head>
<body>
  <div class="container">
    
      <div class="form-side">
        <h2>Log in</h2>
        <form class="login__form" method="get" action="">
            
            
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
            <button type="submit" class="login__button">Log In</button>
        </form>

        <div class="links">
          <a href="#">Forgot Password?</a>
        </div>
        <div class="socials">
<span class="social-title">Log In with</span>
  <div class="social-icons">
    <img width="30" height="30" src="https://img.icons8.com/ios-glyphs/30/google-logo--v1.png" alt="google-logo--v1"/>
    <img width="30" height="30" src="https://img.icons8.com/ios-glyphs/30/facebook-new.png" alt="facebook-new"/>
    <img width="50" height="50" src="https://img.icons8.com/ios/50/instagram-new--v1.png" alt="instagram-new--v1"/>
    <img src="https://img.icons8.com/ios-glyphs/30/linkedin.png" alt="LinkedIn" />
  </div>
</div>
      </div>
      <div class="image-side">
        <!-- Inserisci direttamente il contenuto SVG oppure usa l'embed -->
        <embed src="college-project-animate.svg" type="image/svg+xml" />
      </div>
    
  </div>
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
