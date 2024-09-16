<?php 

header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Origin: *');

// Database connection
$host = "localhost";
$db_name = "voting_system";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $e->getMessage()]);
    exit();
}

// Prepare the SQL query
$sql = "SELECT teacher_id, teacher_name FROM teacher";

// Execute the SQL statement
try {
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Fetch all results
    $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($teachers) {
        echo json_encode(["status" => "success", "teachers" => $teachers]);
    } else {
        echo json_encode(["status" => "success", "teachers" => []]);
    }
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Query failed: " . $e->getMessage()]);
}
?>
