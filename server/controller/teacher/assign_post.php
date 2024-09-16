<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');

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

// Get the POST data (teacher_id, grade_id)
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['teacher_id']) && isset($data['grade_id'])) {
    $teacher_id = $data['teacher_id'];
    $grade_id = $data['grade_id'];

    // Prepare the SQL query to insert into the grade_teacher table
    $sql = "INSERT INTO grade_teacher (teacher_id, grade_id) VALUES (:teacher_id, :grade_id)";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':teacher_id', $teacher_id);
        $stmt->bindParam(':grade_id', $grade_id);
        
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Teacher assigned to grade successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to assign teacher to grade."]);
        }
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Query failed: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid input data."]);
}
?>
