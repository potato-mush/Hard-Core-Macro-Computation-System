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

    <!-- Forgot Password Modal -->
    <div id="forgotPasswordModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Reset Password</h2>
            <p>Enter your email address to receive a new password.</p>
            <form id="forgotPasswordForm">
                <input type="email" id="reset-email" name="email" placeholder="Enter your email" required>
                <button type="submit">Reset Password</button>
            </form>
        </div>
    </div>

    <div class="success-message" id="successMessage"></div>

    <!-- Token Verification Modal -->
    <div id="tokenVerificationModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeTokenModal()">&times;</span>
            <div class="modal-header">
                <h2>Enter Reset Token</h2>
                <p>Please enter the token you received to continue with password reset.</p>
            </div>
            <form id="tokenVerificationForm">
                <input type="text" id="reset-token" name="token" placeholder="Enter your token" required>
                <button type="submit">Verify Token</button>
            </form>
        </div>
    </div>

    <!-- New Password Modal -->
    <div id="newPasswordModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closePasswordModal()">&times;</span>
            <div class="modal-header">
                <h2>Set New Password</h2>
                <p>Create a strong password for your account.</p>
            </div>
            <form id="newPasswordForm">
                <div class="password-requirements">
                    <strong>Password Requirements:</strong>
                    <ul>
                        <li>At least 8 characters long</li>
                        <li>Contains uppercase letters</li>
                        <li>Contains lowercase letters</li>
                        <li>Contains numbers</li>
                        <li>Contains special characters</li>
                    </ul>
                </div>
                <div class="password-field-group">
                    <input type="password" id="new-password" name="password" 
                           placeholder="New Password" required>
                    <i class="fas fa-eye toggle-password"></i>
                    <div class="password-strength"></div>
                </div>
                <div class="password-field-group">
                    <input type="password" id="confirm-password" name="confirm_password" 
                           placeholder="Confirm Password" required>
                    <i class="fas fa-eye toggle-password"></i>
                </div>
                <input type="hidden" id="current-token" name="token">
                <button type="submit">Reset Password</button>
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
                    errorMessage.style.display = 'none';
                    errorMessage.style.opacity = 1;
                }, 1000);
            }, 3000);
        <?php endif; ?>

        // Forgot Password Modal
        const forgotPasswordLink = document.querySelector('a[href="#"]');
        const modal = document.getElementById('forgotPasswordModal');
        const successMessage = document.getElementById('successMessage');

        forgotPasswordLink.addEventListener('click', function(e) {
            e.preventDefault();
            modal.style.display = 'flex';
        });

        window.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });

        document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = document.getElementById('reset-email').value.trim();
            console.log('Checking email:', email);

            fetch('forgot-password.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `email=${encodeURIComponent(email)}&check=true`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Server response:', data);
                
                if (data.debug) {
                    console.log('Debug info:', data.debug);
                }
                
                if (!data.exists) {
                    throw new Error(data.message || 'Email not found');
                }

                // Continue with password reset
                return fetch('forgot-password.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `email=${encodeURIComponent(email)}`
                }).then(response => response.json());
            })
            .then(data => {
                console.log('Password reset response:', data);
                
                modal.style.display = 'none';
                successMessage.innerHTML = ''; // Clear previous content
                
                // Create message container
                const messageDiv = document.createElement('div');
                messageDiv.innerText = data.message;
                successMessage.appendChild(messageDiv);
                
                // If token exists, show it
                if (data.token) {
                    const tokenDiv = document.createElement('div');
                    tokenDiv.style.marginTop = '10px';
                    tokenDiv.style.fontSize = '20px';
                    tokenDiv.style.fontWeight = 'bold';
                    tokenDiv.style.wordBreak = 'break-all';
                    tokenDiv.innerText = data.token;
                    successMessage.appendChild(tokenDiv);
                    
                    // Add instructions
                    const instructionDiv = document.createElement('div');
                    instructionDiv.style.marginTop = '10px';
                    instructionDiv.innerHTML = 'Click "Continue" to enter this token and set your new password';
                    successMessage.appendChild(instructionDiv);
                    
                    // Add continue button
                    const continueBtn = document.createElement('button');
                    continueBtn.innerText = 'Continue';
                    continueBtn.style.marginTop = '10px';
                    continueBtn.style.padding = '5px 15px';
                    continueBtn.style.cursor = 'pointer';
                    continueBtn.onclick = function() {
                        successMessage.style.display = 'none';
                        document.getElementById('tokenVerificationModal').style.display = 'flex';
                    };
                    successMessage.appendChild(continueBtn);
                }
                
                successMessage.style.display = 'block';
                successMessage.style.backgroundColor = 
                    data.status === 'success' ? 'rgb(0, 167, 0)' : 'rgba(255, 0, 0, 0.8)';
                
                if (data.status === 'success') {
                    document.getElementById('reset-email').value = '';
                }
            })
            .catch(error => {
                console.error('Error details:', error);
                successMessage.innerText = error.message || 'An error occurred';
                successMessage.style.display = 'block';
                successMessage.style.backgroundColor = 'rgba(255, 0, 0, 0.8)';
                
                setTimeout(() => {
                    successMessage.style.opacity = 0;
                    setTimeout(() => {
                        successMessage.style.display = 'none';
                        successMessage.style.opacity = 1;
                    }, 1000);
                }, 3000);
            });
        });

        // Add new event listeners for token verification and password reset
        document.getElementById('tokenVerificationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const token = document.getElementById('reset-token').value.trim();

            fetch('reset-password.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `token=${encodeURIComponent(token)}&action=verify`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('tokenVerificationModal').style.display = 'none';
                    document.getElementById('current-token').value = token;
                    document.getElementById('newPasswordModal').style.display = 'flex';
                } else {
                    throw new Error(data.message || 'Invalid token');
                }
            })
            .catch(error => {
                successMessage.innerText = error.message;
                successMessage.style.display = 'block';
                successMessage.style.backgroundColor = 'rgba(255, 0, 0, 0.8)';
            });
        });

        document.getElementById('newPasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const password = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            const token = document.getElementById('current-token').value;

            if (password !== confirmPassword) {
                successMessage.innerText = 'Passwords do not match';
                successMessage.style.display = 'block';
                successMessage.style.backgroundColor = 'rgba(255, 0, 0, 0.8)';
                return;
            }

            fetch('reset-password.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `token=${encodeURIComponent(token)}&password=${encodeURIComponent(password)}&action=reset`
            })
            .then(response => response.json())
            .then(data => {
                successMessage.innerText = data.message;
                successMessage.style.display = 'block';
                successMessage.style.backgroundColor = 
                    data.status === 'success' ? 'rgb(0, 167, 0)' : 'rgba(255, 0, 0, 0.8)';
                
                if (data.status === 'success') {
                    document.getElementById('newPasswordModal').style.display = 'none';
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                }
            })
            .catch(error => {
                successMessage.innerText = error.message || 'An error occurred';
                successMessage.style.display = 'block';
                successMessage.style.backgroundColor = 'rgba(255, 0, 0, 0.8)';
            });
        });

        function closeTokenModal() {
            document.getElementById('tokenVerificationModal').style.display = 'none';
        }

        function closePasswordModal() {
            document.getElementById('newPasswordModal').style.display = 'none';
        }

        // Add close button functionality
        document.querySelector('.close-modal').addEventListener('click', function() {
            modal.style.display = 'none';
        });

        // Add password visibility toggle for new password modal
        document.querySelectorAll('#newPasswordModal .toggle-password').forEach(function(toggle) {
            toggle.addEventListener('click', function() {
                const input = this.previousElementSibling;
                const type = input.type === 'password' ? 'text' : 'password';
                input.type = type;
                this.classList.toggle('fa-eye-slash');
            });
        });

        // Add password strength checker
        document.getElementById('new-password').addEventListener('input', function() {
            const password = this.value;
            const strength = checkPasswordStrength(password);
            const strengthDiv = this.parentElement.querySelector('.password-strength');
            
            strengthDiv.textContent = strength.message;
            strengthDiv.className = 'password-strength ' + strength.class;
            
            // Update requirement list
            const requirements = document.querySelectorAll('.password-requirements li');
            requirements[0].classList.toggle('met', password.length >= 8);
            requirements[1].classList.toggle('met', /[A-Z]/.test(password));
            requirements[2].classList.toggle('met', /[a-z]/.test(password));
            requirements[3].classList.toggle('met', /[0-9]/.test(password));
            requirements[4].classList.toggle('met', /[^A-Za-z0-9]/.test(password));
        });

        function checkPasswordStrength(password) {
            if (password.length < 8) {
                return { message: 'Weak password', class: 'weak' };
            }
            
            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            if (strength < 3) return { message: 'Weak password', class: 'weak' };
            if (strength < 5) return { message: 'Medium strength password', class: 'medium' };
            return { message: 'Strong password', class: 'strong' };
        }
    </script>
</body>

</html>