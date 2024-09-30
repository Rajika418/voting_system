<?php
// Database connection
$host = "localhost"; // Your database host
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "voting_system"; // Your database name

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'year' is passed as a parameter
if (isset($_GET['year']) && is_numeric($_GET['year'])) {
    $year = intval($_GET['year']); // Convert the 'year' parameter to an integer
    
    // SQL query to get students based on the passed year in the grade table
    $sql = "SELECT s.student_id, s.student_name, s.father_name, s.user_id, s.grade_id, s.address, s.guardian, s.contact_number, s.registration_number, s.join_date, s.leave_date 
            FROM student s
            JOIN grade g ON s.grade_id = g.grade_id
            WHERE g.year = ?";
    
    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $year);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $students = array();

    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
        // Send response as JSON
        echo json_encode(array('status' => 'success', 'data' => $students));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'No students found for year ' . $year));
    }
    
    // Close statement
    $stmt->close();
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Year parameter is missing or invalid.'));
}

// Close connection
$conn->close();
?>
