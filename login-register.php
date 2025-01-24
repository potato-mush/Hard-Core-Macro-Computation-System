<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="login-styles.css">
    <title>Login and Signup</title>
</head>

<body>
    <div class="container">
        <!-- Cover Div -->
        <div class="cover slide-right" id="cover">
            <div class="cover-header">
                <span id="cover-action">Don't Have an account Yet?</span>
                <p id="cover-text">Let's Get you all set up so you can start your shopping experience with us!</p>
            </div>
            <button class="toggle-btn" id="toggle-btn">Sign up</button>
        </div>

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

        <!-- Signup Section -->
        <div class="form-box" id="signup-section">
            <h2>Sign Up for an Account</h2>
            <p>Let's get you all set up so you can start your shopping experience with us!</p>
            <form action="signup.php" method="POST">
                <div class="form-row">
                    <input type="text" id="signup-first-name" name="first_name" placeholder="Enter your first name" required>
                    <input type="text" id="signup-last-name" name="last_name" placeholder="Enter your last name" required>
                </div>
                <input type="email" id="signup-email" name="email" placeholder="Enter your email" required>
                <input type="text" id="signup-address" name="address" placeholder="Enter your address" required>
                <input type="tel" id="signup-phone" name="phone" placeholder="Enter your phone number" required>
                <div class="password-wrapper">
                    <input type="password" id="signup-password" name="password" placeholder="Enter your password" required>
                    <i class="fas fa-eye toggle-password" id="signup-toggle-password"></i>
                </div>
                <div class="terms-checkbox">
                    <label>
                        <input type="checkbox" name="terms" required> I agree to the <a href="#">Terms & Conditions</a>
                    </label>
                </div>
                <button type="submit">Signup</button>
            </form>
        </div>
    </div>

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

        // Apply toggle functionality to both forms
        togglePasswordVisibility('login-password', 'login-toggle-password');
        togglePasswordVisibility('signup-password', 'signup-toggle-password');

        // Toggle between login and signup sections
        const toggleButton = document.getElementById("toggle-btn");
        const cover = document.getElementById("cover");
        const coverText = document.getElementById("cover-text");
        const coverAction = document.getElementById("cover-action");

        toggleButton.addEventListener("click", function() {
            // Slide the cover left or right
            cover.classList.toggle("slide-right");
            cover.classList.toggle("slide-left");

            // Change the button text and cover text based on state
            if (cover.classList.contains("slide-right")) {
                toggleButton.textContent = "Sign up";
                coverText.textContent = "Let's Get you all set up so you can start your shopping experience with us!";
                coverAction.textContent = "Don't Have an account Yet?";
            } else {
                toggleButton.textContent = "Login";
                coverText.textContent = "Log in to your account so you can continue shopping with us!";
                coverAction.textContent = "Already Signed Up?";
            }
        });
    </script>
</body>

</html>