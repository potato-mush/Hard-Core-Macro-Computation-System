<?php
include('functions/login.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $error = login($email, $password, $pdo);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/styles/login-styles.css">
    <title>Login</title>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
            opacity: 1;
            transition: opacity 1s ease-out;
        }

        .error-message {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: rgba(255, 0, 0, 0.8);
            color: white;
            text-align: center;
            padding: 10px;
            font-size: 1.2rem;
            z-index: 1000;
            opacity: 1;
            transition: opacity 1s ease-out;
        }

        .back-button {
            display: block;
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            text-decoration: none;
            color: #007bff;
            font-size: 16px;
        }

        .back-button:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="error-message" id="errorMessage"></div>

    <div class="container">
        <!-- Login Section -->
        <div class="form-box" id="login-section">
            <h2>Login to Your Account</h2>
            <p>Welcome back! Please login to your account to continue.</p>
            <form action="login.php" method="POST">
                <input type="email" id="login-email" name="email" placeholder="Enter your email" required>
                <div class="password-wrapper">
                    <input type="password" id="login-password" name="password" placeholder="Enter your password" required>
                    <i class="fas fa-eye toggle-password" id="login-toggle-password"></i>
                </div>
                <div class="form-footer">
                    <label>
                        <input type="checkbox" name="remember"> Remember Me
                    </label>
                    <a href="#">Forgot Password?</a>
                </div>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
    <a href="index.php" class="back-button">Back to Home</a>

    <script>
        // Toggle password visibility function
        function togglePasswordVisibility(passwordFieldId, toggleIconId) {
            const passwordField = document.getElementById(passwordFieldId);
            const toggleIcon = document.getElementById(toggleIconId);

            toggleIcon.addEventListener('click', function() {
                // Toggle the input type between 'password' and 'text'
                const type = passwordField.type === 'password' ? 'text' : 'password';
                passwordField.type = type;

                // Toggle the icon (eye and eye-slash)
                toggleIcon.classList.toggle('fa-eye-slash');
            });
        }

        // Apply toggle functionality to the login form
        togglePasswordVisibility('login-password', 'login-toggle-password');

        // Show error message if exists
        <?php if (!empty($error)): ?>
            const errorMessage = document.getElementById('errorMessage');
            errorMessage.innerText = "<?php echo $error; ?>";
            errorMessage.style.display = "block";
            setTimeout(() => {
                errorMessage.style.opacity = 0;
                setTimeout(() => {
                    errorMessage.style.display = "none";
                    errorMessage.style.opacity = 1;
                }, 1000);
            }, 3000);
        <?php endif; ?>
    </script>
</body>

</html>