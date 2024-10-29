<?php

require '../../db_config.php';

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Extract user_id from the URL
    $path_info = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
    $user_id = end($path_info);

    // Check if user_id is provided and is numeric
    if (empty($user_id) || !is_numeric($user_id)) {
        echo json_encode(["status" => "error", "message" => "Invalid or missing user ID"]);
        exit();
    }

    // Get the current and new password from POST data
    $current_password = $_POST['current_password'] ?? null;
    $new_password = $_POST['new_password'] ?? null;

    if (empty($current_password) || empty($new_password)) {
        echo json_encode(["status" => "error", "message" => "Please provide both current and new passwords"]);
        exit();
    }

    try {
        // Fetch the user's current hashed password from the database
        $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo json_encode(["status" => "error", "message" => "User not found"]);
            exit();
        }

        // Verify the current password
        if (!password_verify($current_password, $user['password'])) {
            echo json_encode(["status" => "error", "message" => "Current password is incorrect"]);
            exit();
        }

        // Hash the new password
        $hashed_new_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Update the password in the database
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        $stmt->execute([$hashed_new_password, $user_id]);

        echo json_encode(["status" => "success", "message" => "Password updated successfully"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Password update failed: " . $e->getMessage()]);
    }
} else {
    // Invalid request method
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
