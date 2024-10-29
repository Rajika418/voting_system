<?php

require '../../db_config.php';

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the input data
    $input = json_decode(file_get_contents('php://input'), true);
    $user_name = $input['user_name'];
    $user_password = $input['password'];
    $email = $input['email'];
    $role_id = 1;

    // Handle the image upload if provided
    $image = null;
    $image_dir = '../../../uploads/';
    $base_url = 'http://localhost/voting_system/uploads/';

    // Create the directory if it doesn't exist
    if (!is_dir($image_dir)) {
        mkdir($image_dir, 0777, true);
    }

    // Check if an image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_name = basename($_FILES['image']['name']);
        $unique_image_name = uniqid() . '_' . $image_name;
        $image_path = $image_dir . $unique_image_name;

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

    // Hash the password
    $hashedPassword = password_hash($user_password, PASSWORD_BCRYPT);

    // Prepare SQL statement to insert new user
    $stmt = $pdo->prepare("INSERT INTO users (user_name, password, email, role_id, image) VALUES (:user_name, :password, :email, :role_id, :image)");
    $stmt->bindParam(':user_name', $user_name);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':role_id', $role_id);
    $stmt->bindParam(':image', $image);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'User created successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create user']);
    }
} else {
    // Invalid request method
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
