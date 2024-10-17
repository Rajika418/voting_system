<?php
// Include the database configuration file
include '../../db_config.php';

// API to get nomination data
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Prepare the SQL query
    $sql = "
        SELECT 
            n.id, 
            n.student_id, 
            s.student_name, 
            s.grade_id, 
            g.grade_name, 
            u.user_id, 
            u.image,
            n.why,
            n.motive,
            n.what
        FROM 
            nomination n
        JOIN 
            student s ON n.student_id = s.student_id
        JOIN 
            users u ON s.user_id = u.user_id
        JOIN 
            grade g ON s.grade_id = g.grade_id
    ";

    try {
        // Execute the query
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // Fetch all results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if results were found
        if ($results) {
            echo json_encode([
                'status' => 'success',
                'data' => $results
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'No nominations found.'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error fetching nominations: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}
?>
