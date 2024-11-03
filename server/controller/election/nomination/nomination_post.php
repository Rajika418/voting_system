<?php
// Include the database configuration file
include '../../../db_config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Origin: *');

// API to insert nomination data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect input data
    $election_id = $_POST['election_id'];
    $student_id = $_POST['student_id'];
    $why = $_POST['why'];
    $motive = $_POST['motive'];
    $what = $_POST['what'];

    // Validate required fields
    if (empty($student_id) || empty($why) || empty($motive) || empty($what)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'All fields are required.'
        ]);
        exit();
    }

    // Check if the student has already applied for a nomination
    try {
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM nomination WHERE student_id = ? AND election_id = ?");
        $checkStmt->execute([$student_id, $election_id]);
        $count = $checkStmt->fetchColumn();

        if ($count > 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'This student has already applied for a nomination in this election.'
            ]);
            exit();
        }
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error checking nomination: ' . $e->getMessage()
        ]);
        exit();
    }

    // Prepare the SQL statement to insert the nomination
    try {
        $stmt = $conn->prepare("INSERT INTO nomination (election_id, student_id, why, motive, what) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$election_id, $student_id, $why, $motive, $what]);

        echo json_encode([
            'status' => 'success',
            'message' => 'Nomination submitted successfully.'
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error submitting nomination: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}
