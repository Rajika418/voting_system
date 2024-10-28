<?php
session_start(); // Start the session at the beginning

require '../db_config.php';

try {
    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Fetch the user data from form submission
        $user_name = $_POST['user_name'];
        $user_password = $_POST['password'];

        // Prepare and execute the SQL statement to fetch user by username
        $stmt = $conn->prepare("SELECT * FROM users WHERE user_name = :user_name");
        $stmt->bindParam(':user_name', $user_name);
        $stmt->execute();

        // Retrieve user details
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists and password is correct
        if ($user && password_verify($user_password, $user['password'])) {
            // Password matches; store user ID in session and set login success
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['login_success'] = true;

            // Determine redirection based on role_id
            if ($user['role_id'] == "1") {
                header("Location: http://localhost/voting_system/client/admin/");
            } else {
                header("Location: http://localhost/voting_system/client/user/");
            }
            exit();
        } else {
            // Invalid credentials; redirect with error message
            header("Location: ../../client/login.html?error=invalid");
            exit();
        }
    } else {
        // Invalid request method; redirect with error
        header("Location: ../../client/login.html?error=method");
        exit();
    }
} catch (PDOException $e) {
    // Log database errors
    error_log("Database error: " . $e->getMessage(), 3, '../log/error_log.log');
    header("Location: ../../client/login.html?error=database");
    exit();
}
