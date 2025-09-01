<?php
$conn = new mysqli("db", "root", "root", "testdb");
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}
$conn->query("SET FOREIGN_KEY_CHECKS==1");

$username = $_GET['username'] ?? '';
$password = $_GET['password'] ?? '';
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Login</title>

        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

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
              transition: all 0.3s ease;
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
              padding: 1rem;
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
            }
        
            .form-side .links {
              font-size: 14px;
              color: #555;
              text-align: center;
              margin-top: -1.7rem;
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
              width: 30px;
              height: 30px;
              object-fit: contain;
              filter: brightness(0);
              transition: transform 0.2s;
              cursor: pointer;
            }
        
            .social-icons img:hover {
              transform: scale(1.1);
            }
        
            .image-side {
              flex-basis: 50%;
              background-color: #f3f1e8;
              display: flex;
              align-items: center;
              justify-content: center;
              padding-right: 50px;
            }
        
            .image-side svg,
            .image-side embed {
              width: 100%;
              max-width: 1000px;
              height: auto;
              margin-left: -200px;
            }
        
            a {
              color: inherit;
              text-decoration: none;
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
            .error-input {
              border-color: red !important;
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
        
            .error-input:focus {
              border-color: red !important;
              box-shadow: 0 0 0 4px rgba(229, 70, 70, 0.1) !important;
            }
            .error-input:focus + .login__label,
            .error-input:not(:placeholder-shown) + .login__label {
              color: red !important;
            }
        
            .error-pass:focus {
              background-color: #fff0f0;
              border-color: red !important;
              color: darkred;
              box-shadow: 0 0 0 4px rgba(229, 70, 70, 0.1);
            }
        
            .error-pass:focus + .login__label {
              color: red !important;
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
              color: rgb(253, 162, 25);
            }
        
            .error-icon:hover {
              color: red !important;
            }
        
            .login__button:hover {
              background-color: rgb(255 199 40);
              transform: translateY(-2px);
            }
        
            .login__button:hover.error {
              background-color: red;
            }
        
            .error {
              display: none;
            }
            .login_box.sideImage {
              background-color: #ffe5e5;
              border-left: 4px solid red;
              transition: background-color 0.3s ease;
            }
            .login__testo{
              display: none ;
            }
            .error-testo{
              display: inline;
              padding-left: 10px;
              color: red;
              margin-top: -23px;
            }
            .success {
              top: 20px;
              right: 20px;
              z-index: 9999;
              background-color: #e6ffed;
              color: #2d6a4f;
              border: 1px solid #95d5b2;
              padding: 12px 20px;
              border-radius: 8px;
              box-shadow: 0 4px 12px rgba(0,0,0,0.1);
              font-weight: 500;
              min-width: 220px;
              animation: slideIn 0.4s ease;
              margin-top: 10px;
              opacity: 0;
              transform: translateX(100%);
              animation: slideIn 0.4s ease forwards;
            }

            @keyframes slideIn {
              from {
                opacity: 0;
                transform: translateX(100%);
              }
              to {
                opacity: 1;
                transform: translateX(0);
              }
            }

            .success-toast-wrapper {
              position: fixed;
              top: 20px;
              right: 0px;
              width: auto;
              max-height: 97%;
              overflow-y: scroll;
              overflow-x: hidden;
              z-index: 9999;
              display: flex;
              flex-direction: column;
              align-items: flex-end;
              gap: 10px;
              padding-right: 8px;
            }

            .success-toast-wrapper::-webkit-scrollbar {
              width: 6px;
            }

            .success-toast-wrapper::-webkit-scrollbar-thumb {
              background-color: rgba(0, 0, 0, 0.2);
              border-radius: 3px;
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
                            <input type="text" id="login-username" class="login__input" name="username" autocomplete="off" value="<?php echo htmlspecialchars($username); ?>" placeholder=" " required>
                            <label for="login-username" class="login__label">Nome Utente</label>

                        </div>
                        <span id="testo" class="login__testo">No account found with this username or password. Please retry.</span>
                        <div class="login__box">
                            <input type="password" id="login-pass" class="login__input" name="password" autocomplete="off" placeholder=" " required>
                            <label for="login-pass" class="login__label">Password</label>
                            <i class="ri-eye-off-line login__eye" id="login-eye"></i>
                        </div>
                    </div>

                    <button id="buttonError" type="submit" class="login__button">Log In</button>
                </form>

                <div class="links">
                    <a href="#">Forgot Password?</a>
                </div>

                <div class="socials">
                    <span class="social-title">Log In with</span>
                    <div class="social-icons">
                        <img src="https://img.icons8.com/ios-glyphs/30/google-logo--v1.png" alt="Google" />
                        <img src="https://img.icons8.com/ios-glyphs/30/facebook-new.png" alt="Facebook" />
                        <img src="https://img.icons8.com/ios/50/instagram-new--v1.png" alt="Instagram" />
                        <img src="https://img.icons8.com/ios-glyphs/30/linkedin.png" alt="LinkedIn" />
                    </div>
                </div>
            </div>

            <div class="image-side">
                <embed id="sideImage" src="college-project-animate.svg" type="image/svg+xml" />
            </div>
        </div>

        <?php
    if (!empty($username) || !empty($password)) {
        $tableExists = $conn->query("SHOW TABLES LIKE 'users'")->num_rows > 0;
        
        if (!$tableExists) {
            echo '<div id="loginError" style="display:none;"></div>';
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    const embed = document.getElementById("sideImage");
                    if(embed) embed.src = "college-project-animate (1).svg";
                    
                    const buttonError = document.getElementById("buttonError");
                    if(buttonError) buttonError.classList.add("error");
                    
                    document.getElementById("login-eye").classList.add("error-icon");
                    document.getElementById("login-username").classList.add("error-input");
                    document.getElementById("login-pass").classList.add("error-pass");
                    document.getElementById("testo").classList.add("error-testo");
                });
            </script>';
        } else {
            
            $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
            
            if ($conn->multi_query($query)) {
                do {
                    if ($result = $conn->store_result()) {
                        echo '<div class="results">';
                        if ($result->num_rows > 0) {
                            echo '<div id="loginSuccess" style="display:none;"></div>'; 
                            echo '<div class="success-toast-wrapper">';
                            while ($row = $result->fetch_assoc()) {
                                echo '<div class="success">';
                                foreach ($row as $value) {
                                    echo htmlspecialchars($value) . "<br>";
                                }
                                echo '</div>';
                            }
                            echo '</div>';
                        } else {
                            echo '<div id="loginError" style="display:none;"></div>';
                        }
                        $result->free();
                        echo '</div>';
                    }
                } while ($conn->more_results() && $conn->next_result());
            } else {
                echo '<div class="error">Errore: ' . $conn->error . '</div>';
            }
        }
    }
    ?>
            <script>
                const showHiddenPass = (loginPass, loginEye) => {
                    const input = document.getElementById(loginPass),
                          iconEye = document.getElementById(loginEye);
                
                    iconEye.addEventListener('click', () => {
                      if (input.type === 'password') {
                        input.type = 'text';
                        iconEye.classList.remove('ri-eye-off-line');
                        iconEye.classList.add('ri-eye-line');
                      } else {
                        input.type = 'password';
                        iconEye.classList.remove('ri-eye-line');
                        iconEye.classList.add('ri-eye-off-line');
                      }
                    });
                  }
                
                  showHiddenPass('login-pass', 'login-eye');
                
                  window.onbeforeunload = function () {
                    window.location.href = window.location.pathname;
                  };
                
                  window.addEventListener('load', () => {
                    if (performance.navigation.type === 1) {
                      document.querySelectorAll('input[type="text"], input[type="password"]').forEach(el => el.value = '');
                      const buttonError = document.getElementById('buttonError');
                      if (buttonError) buttonError.classList.remove('error');
                      document.getElementById('login-eye').classList.remove('error-icon');
                      const usernameInput = document.getElementById('login-username');
                      if (usernameInput) usernameInput.classList.remove('error-input');
                      const passwordInput = document.getElementById('login-pass');
                      if (passwordInput) passwordInput.classList.remove('error-pass');
                      const testo = document.getElementById('testo');
                      if (testo) testo.classList.remove('error-testo');
                      document.querySelectorAll('.error, .success').forEach(el => el.remove());
                      const embed = document.getElementById('sideImage');
                      if (embed) embed.src = 'college-project-animate.svg';
                      window.history.replaceState({}, document.title, window.location.pathname);
                    }
                  });
                
                  if (document.getElementById('loginError')) {
                    document.getElementById('login-username').value = '';
                    const embed = document.getElementById('sideImage');
                    if (embed) embed.src = 'college-project-animate (1).svg';
                    const buttonError = document.getElementById('buttonError');
                    if (buttonError) buttonError.classList.add('error');
                    document.getElementById('login-eye').classList.add('error-icon');
                    const usernameInput = document.getElementById('login-username');
                    if (usernameInput) usernameInput.classList.add('error-input');
                    const passwordInput = document.getElementById('login-pass');
                    if (passwordInput) passwordInput.classList.add('error-pass');
                    const testo = document.getElementById('testo');
                    if (testo) testo.classList.add('error-testo');
                  }
                  if (document.getElementById('loginSuccess')) {
                    document.getElementById('login-username').value = '';
                  const usernameInput = document.getElementById('login-username');
                  const passwordInput = document.getElementById('login-pass');
                  const buttonError = document.getElementById('buttonError');
                  const loginEye = document.getElementById('login-eye');
                  const testo = document.getElementById('testo');
                
                  usernameInput?.classList.remove('error-input');
                  passwordInput?.classList.remove('error-pass');
                  buttonError?.classList.remove('error');
                  loginEye?.classList.remove('error-icon');
                  testo?.classList.remove('error-testo')
                }
                const wrapper = document.querySelector('.success-toast-wrapper');
                if (wrapper && wrapper.children.length <= 1) {
                  wrapper.style.overflowY = 'hidden';
                } 
                window.addEventListener("DOMContentLoaded", () => {
                  const toasts = document.querySelectorAll('.success');
                  toasts.forEach((toast, index) => {
                    toast.style.animationDelay = `${index * 0.1}s`;
                  });
                });
            </script>
    </body>
    </html>
    <?php
$conn->close();
?>