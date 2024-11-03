<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Origin: *');

// Include database configuration
require '../../db_config.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Parse the student ID from the query string
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $student_id = $_GET['id'];
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Missing student ID"
        ]);
        exit();
    }

    // Start database transaction
    $conn->beginTransaction();

    try {
        // Fetch user_id before deleting the student
        $stmt_fetch_user = $conn->prepare("SELECT user_id FROM student WHERE student_id = ?");
        $stmt_fetch_user->execute([$student_id]);
        $user_id = $stmt_fetch_user->fetchColumn();

        if (!$user_id) {
            throw new Exception("User not found for the given student ID");
        }

        // Delete the student data from the student table
        $stmt_student = $conn->prepare("DELETE FROM student WHERE student_id = ?");
        $stmt_student->execute([$student_id]);

        // Delete the user data from the users table
        $stmt_user = $conn->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt_user->execute([$user_id]);

        // Commit the transaction
        $conn->commit();

        echo json_encode([
            "status" => "success",
            "message" => "Student deletion successful"
        ]);
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollBack();
        error_log("Error: " . $e->getMessage()); // Log the error
        echo json_encode([
            "status" => "error",
            "message" => "Student deletion failed: " . $e->getMessage()
        ]);
    }
}
