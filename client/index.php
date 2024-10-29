<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // Check the user's role to determine the redirection
    if ($_SESSION['role_id'] == "1") {
        header("Location: http://localhost/voting_system/client/admin/admin.php");
        exit();
    } else {
        header("Location: http://localhost/voting_system/client/user/home.php");
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
    <script src="./index.js"> </script>
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