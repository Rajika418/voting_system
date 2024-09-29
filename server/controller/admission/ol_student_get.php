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

// SQL query to get students where year is 11 in the grade table
$sql = "SELECT s.student_id, s.student_name, s.father_name, s.user_id, s.grade_id, s.address, s.guardian, s.contact_number, s.registration_number, s.join_date, s.leave_date 
        FROM student s
        JOIN grade g ON s.grade_id = g.grade_id
        WHERE g.year = 11";

$result = $conn->query($sql);

$students = array();

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
    // Send response as JSON
    echo json_encode(array('status' => 'success', 'data' => $students));
} else {
    echo json_encode(array('status' => 'error', 'message' => 'No students found for year 11.'));
}

// Close connection
$conn->close();
?>
