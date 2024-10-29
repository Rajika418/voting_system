<?php

require '../../db_config.php';

// Check if request method is GET
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Check if user_id is provided
    if (empty($_GET['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Missing user ID']);
        exit();
    }

    $user_id = $_GET['user_id'];

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
