<?php
session_start();
require_once 'database.php';

$error = '';
$email = '';
$success_message = '';

if (isset($_GET['logout'])) {
    session_destroy();
    $success_message = "You have been logged out successfully";
}

if (isset($_GET['reset'])) {
    $success_message = "Password reset successfully. Please login with your new password";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
            $stmt->execute([$email, $password]);
            $user = $stmt->fetch();
            
            if ($user) {
                $_SESSION['user'] = $user;
                header('Location: ' . ($user['role'] == 'admin' ? 'admin.php' : 'index.php'));
                exit;
            } else {
                $error = "Invalid email or password";
            }
        } catch (PDOException $e) {
            $error = "Login service is currently unavailable";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | University Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="loginS.css">
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>Welcome Back</h1>
        </div>
        
        <form method="post" class="login-form" id="loginForm">
            <?php if ($error): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <?php if ($success_message): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success_message) ?>
                </div>
            <?php endif; ?>
            
            <div class="floating-label">
                <input type="email" id="email" name="email" placeholder=" " 
                       value="<?= htmlspecialchars($email) ?>" required>
                <label for="email">Email Address</label>
                <i class="fas fa-envelope input-icon"></i>
            </div>
            
            <div class="floating-label">
                <input type="password" id="password" name="password" placeholder=" " required>
                <label for="password">Password</label>
                <i class="fas fa-lock input-icon"></i>
                <span class="password-toggle" id="togglePassword">
                    <i class="far fa-eye"></i>
                </span>
            </div>
            
            <div class="password-strength" id="passwordStrength">
                <div class="strength-meter"></div>
            </div>
            
            <div class="form-options">
                <label class="remember-me">
                    <input type="checkbox" name="remember"> Remember me
                </label>
                <a href="forgot-password.php" class="forgot-link">Forgot password?</a>
            </div>
            
            <button type="submit" class="login-btn" id="loginButton">
                <span class="button-text">Login</span>
                <i class="fas fa-spinner fa-spin loading-icon"></i>
            </button>
            
            <div class="social-login">
                <p>Or login with</p>
                <div class="social-icons">
                    <a href="#" class="social-icon" title="Login with Google">
                        <i class="fab fa-google"></i>
                    </a>
                    <a href="#" class="social-icon" title="Login with Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social-icon" title="Login with Microsoft">
                        <i class="fab fa-microsoft"></i>
                    </a>
                </div>
            </div>
        </form>
        
        <div class="footer-links">
            <span>Don't have an account?</span>
            <a href="signup.php" class="signup-link">Sign up</a>
        </div>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="far fa-eye"></i>' : '<i class="far fa-eye-slash"></i>';
        });

        const loginForm = document.getElementById('loginForm');
        const loginButton = document.getElementById('loginButton');
        const buttonText = document.querySelector('.button-text');
        const loadingIcon = document.querySelector('.loading-icon');
        
        loginForm.addEventListener('submit', function() {
            buttonText.textContent = 'Authenticating...';
            loadingIcon.style.display = 'inline-block';
            loginButton.disabled = true;
        });

        password.addEventListener('input', function() {
            const strengthMeter = document.querySelector('.strength-meter');
            const strength = calculatePasswordStrength(this.value);
            
            strengthMeter.style.width = strength.percentage + '%';
            strengthMeter.style.backgroundColor = strength.color;
        });

        function calculatePasswordStrength(password) {
            let strength = 0;
            
            if (password.length > 0) strength += 10;
            if (password.length >= 8) strength += 20;
            
            if (/[A-Z]/.test(password)) strength += 15;
            if (/[a-z]/.test(password)) strength += 15;
            if (/[0-9]/.test(password)) strength += 20;
            if (/[^A-Za-z0-9]/.test(password)) strength += 20;
            
            
            strength = Math.min(strength, 100);
            
            // Determine color
            let color;
            if (strength < 30) color = '#f72585';
            else if (strength < 70) color = '#f8961e';
            else color = '#4cc9f0';
            
            return { percentage: strength, color: color };
        }
    </script>
</body>
</html>