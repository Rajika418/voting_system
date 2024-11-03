<?php
header('Content-Type: application/json');
require '../../db_config.php'; // Include your database connection file

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['grade_id'])) {
    $grade_id = $_GET['grade_id'];

    // Prepare SQL statement to get students
    $stmt = $conn->prepare("SELECT s.student_id, s.student_name, s.father_name, s.grade_id 
                             FROM student s 
                             WHERE s.grade_id = :grade_id");
    $stmt->bindValue(':grade_id', $grade_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch grade details
        $stmt = $conn->prepare("SELECT g.grade_name FROM grade g WHERE g.grade_id = :grade_id");
        $stmt->bindValue(':grade_id', $grade_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $grade_result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if grade was found
            if ($grade_result) {
                $response = [
                    'grade_name' => $grade_result['grade_name'],
                    'students' => $students,
                    'count' => count($students)
                ];
            } else {
                $response = ['error' => 'Grade not found'];
            }
        } else {
            $response = ['error' => 'Failed to fetch grade details'];
        }
    } else {
        $response = ['error' => 'Failed to execute student query'];
    }

    echo json_encode($response);
} else {
    echo json_encode(['error' => 'No grade ID provided']);
}

$conn = null; // Close the connection
