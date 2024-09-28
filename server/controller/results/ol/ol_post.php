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
    $conn = new PDO("mysql:host=" . $host . ";dbname=" . $db_name, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get posted data
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data)) {
        // Prepare SQL statement
        $query = "INSERT INTO results (subject_id, result, index_no) VALUES (:subject_id, :result, :index_no)";
        $stmt = $conn->prepare($query);

        // Insert each result
        $successful_inserts = 0;
        foreach ($data as $result) {
            $stmt->bindParam(":subject_id", $result->subject_id);
            $stmt->bindParam(":result", $result->result);
            $stmt->bindParam(":index_no", $result->index_no);

            if ($stmt->execute()) {
                $successful_inserts++;
            }
        }

        // Check if all inserts were successful
        if ($successful_inserts == count($data)) {
            http_response_code(201);
            echo json_encode(array("message" => "All results were submitted successfully."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to submit all results."));
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