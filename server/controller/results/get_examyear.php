<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Include database configuration file
require '../../db_config.php';

// Query to get unique years from student_exam table
try {
    $stmt = $conn->prepare("SELECT DISTINCT year FROM student_exam");
    $stmt->execute();

    $years = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if any years were found
    if ($years) {
        echo json_encode(array("status" => "success", "years" => $years));
    } else {
        echo json_encode(array("status" => "error", "message" => "No years found."));
    }

} catch (PDOException $e) {
    echo json_encode(array("status" => "error", "message" => $e->getMessage()));
}

$conn = null; // Close the connection
?>
