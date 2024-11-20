<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['role_id'])) {
    // Navigate based on role_id
    switch ($_SESSION['role_id']) {
        case 1: // Admin role
            header("Location: ../client/admin/admin.php");
            break;
        case 2: // Teacher role
            header("Location: ../client/user/main.php");
            break;
        case 3: // Student role
            header("Location: ../client/user/main.php");
            break;
        default: // Unknown role, redirect to login
            session_destroy(); // Clear the session
            header("Location: login.php");
            break;
    }
    exit();
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
    <link rel="icon" href="./admin/assets/images/school.png" type="image/png">
</head>
<body>

<header>
    <div class="header-left">
        <img src="../client/admin/assets/images/school.png" alt="School Logo">
        <div class="school-name">
            <h2>Kalaimahal TMV.Hopton</h2>
        </div>
    </div>
    <div class="system-short-name">kalaiEdu Connect</div>
</header>

<h1 class="system-title">School Management System</h1>

<div class="container">
    <div class="welcome-section">
        <div class="welcome-text">
            <h1>Welcome to the System!</h1>
            <p>Experience seamless education management with our comprehensive school management system. Streamlining administrative tasks, enhancing communication, and empowering educational excellence.</p>
        </div>
    </div>

    <div class="login-section">
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
</div>

<!-- Popup HTML -->
<div id="registerPopup" class="popup">
    <div class="popup-content">
       
        <button class="popup-close" id="closePopup">&times;</button> <!-- Close icon -->
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
