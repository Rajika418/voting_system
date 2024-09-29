<?php
// submit_results.php

// Enable CORS (Cross-Origin Resource Sharing)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Database connection details
$host = "localhost";
$db_name = "voting_system";
$username = "root";
$password = "";

try {
    // Establishing database connection
    $conn = new PDO("mysql:host=" . $host . ";dbname=" . $db_name, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get posted data
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data)) {
        // Prepare the SQL to check if the index_no exists in student_exam and retrieve exam_id
        $check_query = "SELECT id FROM student_exam WHERE index_no = :index_no";

        // Prepare the SQL to insert into results table
        $insert_query = "INSERT INTO results (exam_id, subject_id, result) VALUES (:exam_id, :subject_id, :result)";

        // Prepare the statements
        $check_stmt = $conn->prepare($check_query);
        $insert_stmt = $conn->prepare($insert_query);

        // Counter for successful inserts
        $successful_inserts = 0;

        foreach ($data as $result) {
            // Bind the index_no for validation
            $check_stmt->bindParam(":index_no", $result->index_no);
            $check_stmt->execute();

            // Check if index_no exists in student_exam table
            if ($check_stmt->rowCount() > 0) {
                // Fetch exam_id (primary key from student_exam)
                $row = $check_stmt->fetch(PDO::FETCH_ASSOC);
                $exam_id = $row['id'];

                // Bind parameters for inserting into results table
                $insert_stmt->bindParam(":exam_id", $exam_id);
                $insert_stmt->bindParam(":subject_id", $result->subject_id);
                $insert_stmt->bindParam(":result", $result->result);

                // Execute the insert query
                if ($insert_stmt->execute()) {
                    $successful_inserts++;
                }
            }
        }

        // Check if all inserts were successful
        if ($successful_inserts == count($data)) {
            http_response_code(201);
            echo json_encode(array("message" => "All results were submitted successfully."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to submit all results. Some index_no values may not exist in the student_exam table."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Unable to submit results. Data is incomplete."));
    }
} catch(PDOException $e) {
    http_response_code(503);
    echo json_encode(array("message" => "Unable to submit results: " . $e->getMessage()));
}

// Close connection
$conn = null;
?>
