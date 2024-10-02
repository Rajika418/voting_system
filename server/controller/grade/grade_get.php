<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Origin: *');

// Include the database configuration file
require '../../db_config.php';

// Retrieve all grades with corresponding teacher details (if available)
try {
    $stmt = $conn->prepare("
        SELECT g.grade_id, g.grade_name, g.year, t.teacher_id, t.teacher_name 
        FROM grade g
        LEFT JOIN teacher t ON g.teacher_id = t.teacher_id
    ");
    $stmt->execute();
    $grades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$grades) {
        echo json_encode(['status' => 'error', 'message' => 'No grades found']);
        exit();
    }

    // Prepare the final result
    $result = [
        'status' => 'success',
        'grades' => []
    ];

    foreach ($grades as $grade) {
        $result['grades'][] = [
            'grade_id' => $grade['grade_id'],
            'grade_name' => $grade['grade_name'],
            'year' => $grade['year'], // Year field included
            'teacher' => !empty($grade['teacher_id']) ? [
                'teacher_id' => $grade['teacher_id'],
                'teacher_name' => $grade['teacher_name']
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
