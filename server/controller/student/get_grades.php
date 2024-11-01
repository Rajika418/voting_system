<?php
header('Content-Type: application/json');

// Include your database connection file
require '../../db_config.php'; // Adjust the path as necessary

try {
    // Prepare SQL statement
    $stmt = $conn->prepare("SELECT grade_id, grade_name FROM grade");
    $stmt->execute();
    
    // Fetch results using fetchAll
    $grades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return JSON response
    echo json_encode($grades);
} catch (Exception $e) {
    // Handle exceptions and return an error message
    echo json_encode(['error' => $e->getMessage()]);
}

// Close the database connection
$conn = null;
?>
