<?php 

header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Origin: *');

// Include the database configuration file
require '../db_config.php';

// Initialize an array to hold counts
$countData = [];

// Define an array with the table names
$tables = ['student', 'subjects', 'teacher', 'elections', 'grade'];

// Iterate through each table and get the count
try {
    foreach ($tables as $table) {
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM $table");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $countData[$table] = (int)$result['total'];
    }

    // Return the counts as a JSON response
    echo json_encode($countData);
} catch (PDOException $e) {
    echo json_encode(["message" => "Query failed: " . $e->getMessage()]);
}

?>
