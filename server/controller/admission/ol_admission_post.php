<?php
header('Content-Type: application/json');

// Get the POST data
$data = json_decode(file_get_contents('php://input'), true);

// Check if required fields are provided
if (isset($data['student_id'], $data['nic'], $data['year'], $data['exam_name'], $data['index_no'])) {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'voting_system');

    if ($conn->connect_error) {
        echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
        exit();
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO student_exam (student_id, nic, year, exam_name, index_no) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $data['student_id'], $data['nic'], $data['year'], $data['exam_name'], $data['index_no']);

    // Execute and check for errors
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Data successfully inserted']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Data insertion failed']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Incomplete data']);
}
?>
