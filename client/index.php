<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // Check the user's role to determine the redirection
    if ($_SESSION['role_id'] == "1") {
        header("Location: http://localhost/voting_system/client/admin/");
        exit();
    } else {
        header("Location: http://localhost/voting_system/client/user/");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <script>
        function showToast(message) {
            var toast = document.getElementById("toast");
            toast.textContent = message;
            toast.className = "show";
            setTimeout(function() {
                toast.className = toast.className.replace("show", "");
            }, 3000);
        }

        window.onload = function() {
            var urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('registration') === 'success') {
                showToast("Registration successful! Please log in.");
            } else if (urlParams.get('login') === 'success') {
                showToast("Login successful!");
            } else if (urlParams.get('error') === 'invalid') {
                showToast("Invalid username or password");
            } else if (urlParams.get('error') === 'method') {
                showToast("Invalid request method");
            } else if (urlParams.get('error') === 'database') {
                showToast("Database error occurred");
            }

            // Get the popup and buttons AFTER the DOM is fully loaded
            const registerPopup = document.getElementById('registerPopup');
            const openRegisterPopup = document.getElementById('openRegisterPopup');
            const closePopup = document.getElementById('closePopup');
            const studentBtn = document.getElementById('studentBtn');
            const teacherBtn = document.getElementById('teacherBtn');

            // Open the popup when the register link is clicked
            openRegisterPopup.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the link from navigating
                registerPopup.style.display = 'flex';
            });

            // Close the popup when close button is clicked
            closePopup.addEventListener('click', function() {
                registerPopup.style.display = 'none';
            });

            // Redirect to the student registration page
            studentBtn.addEventListener('click', function() {
                window.location.href = 'http://localhost/voting_system/client/user/pages/student_register.html'; // Replace with your student registration URL
            });

            // Redirect to the teacher registration page
            teacherBtn.addEventListener('click', function() {
                window.location.href = 'http://localhost/voting_system/client/user/pages/teacher_register.html'; // Replace with your teacher registration URL
            });

            // Close the popup if clicked outside the content
            window.addEventListener('click', function(event) {
                if (event.target === registerPopup) {
                    registerPopup.style.display = 'none';
                }
            });

            // Get forgot password elements
            const forgotPasswordLink = document.querySelector('.remember-forgot a');
            const forgotPasswordPopup = document.getElementById('forgotPasswordPopup');
            const forgotPasswordForm = document.getElementById('forgotPasswordForm');

            // Add click event to forgot password link
            forgotPasswordLink.addEventListener('click', function(event) {
                event.preventDefault();
                forgotPasswordPopup.style.display = 'flex';
            });

            // Close forgot password popup when clicking outside
            forgotPasswordPopup.addEventListener('click', function(event) {
                if (event.target === forgotPasswordPopup) {
                    forgotPasswordPopup.style.display = 'none';
                }
            });

            // Close button functionality for forgot password popup
            const forgotPasswordClose = forgotPasswordPopup.querySelector('.popup-close');
            forgotPasswordClose.addEventListener('click', function() {
                forgotPasswordPopup.style.display = 'none';
            });

            // Handle forgot password form submission
            forgotPasswordForm.addEventListener('submit', function(event) {
                event.preventDefault();
                const email = this.querySelector('input[name="email"]').value;

                // Here you would typically make an API call to handle password reset
                // For now, we'll just show a success message
                showToast("Password reset link sent to your email!");
                forgotPasswordPopup.style.display = 'none';
                this.reset();
            });

        };
    </script>
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <header>
        <img src="../client/admin/assets/images/school.png" alt="School Logo">
        <h2>Kalaimahal TMV.Hopton</h2>
        <h1>School Management System</h1>
    </header>

    <div class="container">
        <div class="login-container">
            <div class="login-box">
                <h2>Login</h2>
                <form action="../server/controller/login.php" method="POST">
                    <div class="input-group">
                        <input type="text" name="user_name" placeholder="Username" required>
                        <span class="icon">&#xf007;</span>
                    </div>
                    <div class="input-group">
                        <input type="password" name="password" placeholder="Password" required>
                        <span class="icon">&#xf023;</span>
                    </div>
                    <div class="remember-forgot">
                        <label><input type="checkbox"> Remember me</label>
                        <a href="#">Forgot password?</a>
                    </div>
                    <button type="submit">Login</button>
                    <div class="register-link">
                        Don't have an account? <a href="#" id="openRegisterPopup">Register</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="welcome-text">
            <p>Welcome.</p>
            <p>Login to the system.</p>
        </div>
    </div>

    <!-- Popup HTML -->
    <div id="registerPopup" class="popup" style="display: none;">
        <div class="popup-content">
            <button class="popup-close" id="closePopup"></button>
            <h2>Select Registration Type</h2>
            <div class="btns">
                <button id="studentBtn" class="popup-btn">Student</button>
                <button id="teacherBtn" class="popup-btn">Teacher</button>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast"></div>

    <div id="forgotPasswordPopup" class="popup">
        <div class="popup-content">
            <button class="popup-close"></button>
            <h2>Reset Password</h2>
            <form class="popup-form" id="forgotPasswordForm">
                <input type="email" name="email" placeholder="Enter your email" required>
                <button type="submit" class="popup-btn">Reset Password</button>
            </form>
        </div>
    </div>
</body>

</html>