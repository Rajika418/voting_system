<?php
header('Content-Type: application/json');

// Include database configuration
require '../../db_config.php';

// Get the POST data
$data = json_decode(file_get_contents('php://input'), true);

// Check if required fields are provided
if (isset($data['student_id'], $data['nic'], $data['year'], $data['exam_name'], $data['index_no'])) {
    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO student_exam (student_id, nic, year, exam_name, index_no) VALUES (:student_id, :nic, :year, :exam_name, :index_no)");

    // Bind parameters
    $stmt->bindParam(':student_id', $data['student_id'], PDO::PARAM_INT);
    $stmt->bindParam(':nic', $data['nic'], PDO::PARAM_STR);
    $stmt->bindParam(':year', $data['year'], PDO::PARAM_STR);
    $stmt->bindParam(':exam_name', $data['exam_name'], PDO::PARAM_STR);
    $stmt->bindParam(':index_no', $data['index_no'], PDO::PARAM_STR);

    // Execute and check for errors
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Data successfully inserted']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Data insertion failed']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Incomplete data']);
}

// Close the connection (optional with PDO, as it's closed automatically when the script ends)
?>
