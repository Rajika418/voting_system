<?php /*
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
?> */

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

    try {
        // Check if the grade_id is already assigned to a different teacher
        $check_sql = "SELECT * FROM grade_teacher WHERE grade_id = :grade_id";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bindParam(':grade_id', $grade_id);
        $check_stmt->execute();

        if ($check_stmt->rowCount() > 0) {
            // If found, delete the existing record for that grade
            $delete_sql = "DELETE FROM grade_teacher WHERE grade_id = :grade_id";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bindParam(':grade_id', $grade_id);
            $delete_stmt->execute();
        }

        // Insert the new teacher_id and grade_id into the grade_teacher table
        $insert_sql = "INSERT INTO grade_teacher (teacher_id, grade_id) VALUES (:teacher_id, :grade_id)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bindParam(':teacher_id', $teacher_id);
        $insert_stmt->bindParam(':grade_id', $grade_id);

        if ($insert_stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Teacher reassigned to grade successfully."]);
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
