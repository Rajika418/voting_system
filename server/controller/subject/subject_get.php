<?php

header('Content-Type: application/json');

// Include database configuration
require '../../db_config.php';

try {
    // Retrieve all subjects with corresponding teacher details (if available)
    $stmt = $conn->prepare("
        SELECT s.subject_id, s.subject_name, t.teacher_id, t.teacher_name 
        FROM subjects s
        LEFT JOIN subject_teacher st ON s.subject_id = st.subject_id
        LEFT JOIN teacher t ON st.teacher_id = t.teacher_id
    ");
    $stmt->execute();
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$subjects) {
        echo json_encode(['status' => 'error', 'message' => 'No subjects found']);
        exit();
    }

    // Prepare the final result
    $result = [
        'status' => 'success',
        'subjects' => []
    ];

    foreach ($subjects as $subject) {
        $result['subjects'][] = [
            'subject_id' => $subject['subject_id'],
            'subject_name' => $subject['subject_name'],
            'teacher' => !empty($subject['teacher_id']) ? [
                'teacher_id' => $subject['teacher_id'],
                'teacher_name' => $subject['teacher_name']
            ] : null
        ];
    }

    // Return the result as JSON
    echo json_encode($result);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}

// Close the connection
$conn = null;
