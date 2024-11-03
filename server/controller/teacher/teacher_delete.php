<?php
require "../../db_config.php";

// Check if the necessary POST data is provided
if (!isset($_POST['teacher_id'])) {
    echo json_encode(["message" => "Incomplete data"]);
    exit();
}

$teacher_id = $_POST['teacher_id'];

try {
    // Begin transaction
    $conn->beginTransaction();

    // Retrieve user_id from teacher_id
    $sql = "SELECT user_id FROM teacher WHERE teacher_id = :teacher_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':teacher_id', $teacher_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        echo json_encode(["message" => "Teacher not found"]);
        exit();
    }
    $user_id = $result['user_id'];

    // Delete from subject_teacher table
    $sql = "DELETE FROM subject_teacher WHERE teacher_id = :teacher_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':teacher_id', $teacher_id);
    $stmt->execute();

    // Delete from teacher table
    $sql = "DELETE FROM teacher WHERE teacher_id = :teacher_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':teacher_id', $teacher_id);
    $stmt->execute();

    // Delete from users table
    $sql = "DELETE FROM users WHERE user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    // Commit transaction
    $conn->commit();

    echo json_encode(["message" => "User and associated teacher record deleted successfully"]);
} catch (Exception $e) {
    // Rollback transaction if something went wrong
    $conn->rollBack();

    echo json_encode(["message" => "Deletion failed: " . $e->getMessage()]);
}
