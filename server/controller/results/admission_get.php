<?php
require "../../../db_config.php";

// Enable CORS (Cross-Origin Resource Sharing)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle the GET request (retrieving data by index_no)
    if (isset($_GET['index_no'])) {
        $index_no = $_GET['index_no'];

        try {
            // Prepare the SQL query to retrieve data by index_no with nic and student_name
            $query = "SELECT se.id, se.student_id, se.exam_name, se.year, se.index_no, se.nic, s.student_name
                      FROM student_exam se
                      JOIN student s ON se.student_id = s.student_id
                      WHERE se.index_no = :index_no";

            // Prepare the statement
            $stmt = $conn->prepare($query);

            // Bind the parameter to the query
            $stmt->bindParam(':index_no', $index_no);

            // Execute the query
            $stmt->execute();

            // Fetch the result
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                // Return the retrieved data as JSON
                echo json_encode([
                    'status' => 'success',
                    'data' => $result
                ]);
            } else {
                // No data found for the given index_no
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No data found for this index number'
                ]);
            }

        } catch (PDOException $e) {
            // Handle any errors
            echo json_encode([
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        }

    } else {
        // If index_no is not provided in the GET request
        echo json_encode([
            'status' => 'error',
            'message' => 'index_no parameter is required'
        ]);
    }
}

// Close connection
$conn = null;
?>

