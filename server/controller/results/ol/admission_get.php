<?php
// Include the database connection file
require "../../../db_config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle the POST request (inserting data)
    try {
        // Check if the required POST parameters are provided
        if (isset($_POST['exam_id'], $_POST['student_id'], $_POST['exam_name'], $_POST['year'], $_POST['index_no'], $_POST['nic'])) {
            
            // Retrieve the POST data
            $exam_id = $_POST['exam_id'];
            $student_id = $_POST['student_id'];
            $exam_name = $_POST['exam_name'];
            $year = $_POST['year'];
            $index_no = $_POST['index_no'];
            $nic = $_POST['nic'];

            // Prepare the SQL INSERT query, now including nic
            $query = "INSERT INTO student_exam (exam_id, student_id, exam_name, year, index_no, nic)
                      VALUES (:exam_id, :student_id, :exam_name, :year, :index_no, :nic)";

            // Prepare the statement using PDO
            $stmt = $conn->prepare($query);

            // Bind the parameters to the query
            $stmt->bindParam(':exam_id', $exam_id);
            $stmt->bindParam(':student_id', $student_id);
            $stmt->bindParam(':exam_name', $exam_name);
            $stmt->bindParam(':year', $year);
            $stmt->bindParam(':index_no', $index_no);
            $stmt->bindParam(':nic', $nic);

            // Execute the query
            if ($stmt->execute()) {
                // Success response
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Data inserted successfully'
                ]);
            } else {
                // Error response if execution fails
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to insert data'
                ]);
            }

        } else {
            // Missing required fields
            echo json_encode([
                'status' => 'error',
                'message' => 'All fields are required'
            ]);
        }

    } catch (PDOException $e) {
        // Handle any errors
        echo json_encode([
            'status' => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle the GET request (retrieving data by index_no)
    if (isset($_GET['index_no'])) {
        $index_no = $_GET['index_no'];

        try {
            // Prepare the SQL query to retrieve data by index_no with nic and student_name
            $query = "SELECT se.exam_id, se.student_id, se.exam_name, se.year, se.index_no, se.nic, s.student_name
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

// Close the database connection
$conn = null;
?>
