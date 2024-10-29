<?php
session_start(); // Start the session at the beginning

require '../db_config.php';

try {
    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Fetch the user data from form submission
        $user_name = $_POST['user_name'];
        $user_password = $_POST['password'];

        // Prepare and execute the SQL statement to fetch user by username with role_name
        $stmt = $conn->prepare("
            SELECT users.*, roles.role_name 
            FROM users 
            JOIN roles ON users.role_id = roles.role_id 
            WHERE users.user_name = :user_name
        ");
        $stmt->bindParam(':user_name', $user_name);
        $stmt->execute();

        // Retrieve user details
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists and password is correct
        if ($user && password_verify($user_password, $user['password'])) {
            // Password matches; store user details in session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['user_name'];
            $_SESSION['image'] = $user['image'];
            $_SESSION['role_id'] = $user['role_id'];
            $_SESSION['role_name'] = $user['role_name'];
            $_SESSION['login_success'] = true;

            // Determine redirection based on role_id
            if ($user['role_id'] == "1") {
                header("Location: http://localhost/voting_system/client/admin/admin.php");
            } else {
                header("Location: http://localhost/voting_system/client/user/home.php");
            }
            exit();
        } else {
            // Invalid credentials; redirect with error message
            header("Location: ../../client/index.php?error=invalid");
            exit();
        }
    } else {
        // Invalid request method; redirect with error
        header("Location: ../../client/index.php?error=method");
        exit();
    }
} catch (PDOException $e) {
    // Log database errors
    error_log("Database error: " . $e->getMessage(), 3, '../log/error_log.log');
    header("Location: ../../client/index.php?error=database");
    exit();
}
?>
