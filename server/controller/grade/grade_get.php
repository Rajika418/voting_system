<?php 
header('Content-Type: application/json');

// Database connection
$host = 'localhost';
$dbname = 'voting_system';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve all grades with corresponding teacher details (if available)
    $stmt = $conn->prepare("
        SELECT g.grade_id, g.grade_name, t.teacher_id, t.teacher_name 
        FROM grade g
        LEFT JOIN grade_teacher gt ON g.grade_id = gt.grade_id
        LEFT JOIN teacher t ON gt.teacher_id = t.teacher_id
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
?>
