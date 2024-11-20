<?php

require '../../db_config.php';

// Check if request method is DELETE
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Extract the user_id from the URL
    $path_info = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
    $user_id = end($path_info);

    // Check if user_id is provided and is numeric
    if (empty($user_id) || !is_numeric($user_id)) {
        echo json_encode(["status" => "error", "message" => "Invalid or missing user ID"]);
        exit();
    }

    // Start database transaction
    $conn->beginTransaction();

    try {
        // Fetch the current image URL from the database
        $stmt = $conn->prepare("SELECT image FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo json_encode(["status" => "error", "message" => "User not found"]);
            exit();
        }

        // Get the image URL and file path
        $image_url = $user['image'];
        $image_path = str_replace('http://localhost/voting_system/uploads/', '../../../uploads/', $image_url); // Adjust this according to your base URL

        // Delete the image file from the server
        if (file_exists($image_path)) {
            unlink($image_path); // Delete the file
        } else {
            echo json_encode(["status" => "warning", "message" => "Image file not found on server"]);
        }

        // Remove the image URL from the database
        $stmt = $conn->prepare("UPDATE users SET image = NULL WHERE user_id = ?");
        $stmt->execute([$user_id]);

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

        echo json_encode(["status" => "success", "message" => "Image deleted successfully"]);
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollBack();
        echo json_encode(["status" => "error", "message" => "Image deletion failed: " . $e->getMessage()]);
    }
} else {
    // Invalid request method
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
