<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');

require "../../db_config.php";

// Get the POST data (teacher_id, grade_id)
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['teacher_id']) && isset($data['grade_id'])) {
    $teacher_id = $data['teacher_id'];
    $grade_id = $data['grade_id'];

    try {
        // Update the teacher_id for the specified grade_id in the grade table
        $update_sql = "UPDATE grade SET teacher_id = :teacher_id WHERE grade_id = :grade_id";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bindParam(':teacher_id', $teacher_id);
        $update_stmt->bindParam(':grade_id', $grade_id);

        if ($update_stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Teacher updated for the grade successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update teacher for the grade."]);
        }
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Query failed: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid input data."]);
}
