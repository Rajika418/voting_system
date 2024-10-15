<?php
// Include database configuration
require '../../db_config.php';  // Adjust the path based on your directory structure

// Set the response header to JSON
header('Content-Type: application/json');

try {
    // Fetch elections from the database
    $sql = "SELECT * FROM elections ORDER BY ele_start_date DESC"; // Adjust as needed
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Fetch all results
    $elections = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if there are elections
    if ($elections) {
        echo json_encode(array("status" => "success", "data" => $elections));
    } else {
        echo json_encode(array("status" => "success", "data" => []));
    }
} catch (PDOException $e) {
    echo json_encode(array("status" => "error", "message" => "Database error: " . $e->getMessage()));
}

$conn = null; // Close the connection
?>
