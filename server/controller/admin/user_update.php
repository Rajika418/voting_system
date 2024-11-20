<?php
session_start();
require '../../db_config.php';

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Extract the user_id from the URL
    $path_info = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
    $user_id = end($path_info);

    // Check if user_id is provided and is numeric
    if (empty($user_id) || !is_numeric($user_id)) {
        echo json_encode(["status" => "error", "message" => "Invalid or missing user ID"]);
        exit();
    }

    // Retrieve form data
    $user_name = $_POST['user_name'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $image = null; // Initialize image variable
    $image_dir = '../../../uploads/';
    $base_url = 'http://localhost/voting_system/uploads/';

    // Handle image upload if provided
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_name = basename($_FILES['image']['name']);
        $unique_image_name = uniqid() . '_' . $image_name;
        $image_path = $image_dir . $unique_image_name;

        // Create the directory if it doesn't exist
        if (!is_dir($image_dir)) {
            mkdir($image_dir, 0777, true);
        }

        if (move_uploaded_file($image_tmp_name, $image_path)) {
            // Full URL with base URL included
            $image = $base_url . $unique_image_name;
        } else {
            echo json_encode(["status" => "error", "message" => "Image upload failed"]);
            exit();
        }
    } elseif (isset($_FILES['image']['error']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        echo json_encode(["status" => "error", "message" => "Image upload error: " . $_FILES['image']['error']]);
        exit();
    }

    // Start database transaction
    $conn->beginTransaction();

    try {
        // Prepare the update statement
        $update_fields = [];
        $update_values = [];

        if ($user_name !== null) {
            $update_fields[] = "user_name = ?";
            $update_values[] = $user_name;
        }
        if ($email !== null) {
            $update_fields[] = "email = ?";
            $update_values[] = $email;
        }
        if ($password !== null) {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $update_fields[] = "password = ?";
            $update_values[] = $hashedPassword;
        }
        if ($image !== null) {
            $update_fields[] = "image = ?";
            $update_values[] = $image;
        }

        // Ensure there are fields to update
        if (count($update_fields) > 0) {
            $update_values[] = $user_id; // Add user ID for WHERE clause
            $sql_user_update = "UPDATE users SET " . implode(', ', $update_fields) . " WHERE user_id = ?";
            $stmt_user = $conn->prepare($sql_user_update);
            $stmt_user->execute($update_values);
        }

        // Fetch updated user data after update
        $sql_fetch_user = "SELECT user_id, user_name, email, image FROM users WHERE user_id = ?";
        $stmt_fetch_user = $conn->prepare($sql_fetch_user);
        $stmt_fetch_user->execute([$user_id]);
        $user = $stmt_fetch_user->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['user_name'];
            $_SESSION['image'] = $user['image'];
        } else {
            // Handle case where user data could not be fetched
            echo json_encode(["status" => "error", "message" => "Failed to retrieve updated user details"]);
            exit();
        }

        // Commit the transaction
        $conn->commit();

        echo json_encode(["status" => "success", "message" => "User update successful"]);
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollBack();
        echo json_encode(["status" => "error", "message" => "User update failed: " . $e->getMessage()]);
    }
} else {
    // Invalid request method
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
