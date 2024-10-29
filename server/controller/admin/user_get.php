<?php

require '../../db_config.php';

// Check if the request method is GET
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Get the path info from the URL
    $path_info = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
    
    // The last segment of the URL is assumed to be the user_id
    $user_id = end($path_info);

    // Check if user_id is provided and is numeric
    if (empty($user_id) || !is_numeric($user_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid or missing user ID']);
        exit();
    }

    // Prepare SQL statement to select user data
    $stmt = $conn->prepare("SELECT user_id, user_name, email, image FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);

    // Fetch the user details
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists
    if ($user) {
        echo json_encode(['status' => 'success', 'data' => $user]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
    }
} else {
    // Invalid request method
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
