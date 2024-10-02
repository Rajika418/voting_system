<?php
header('Content-Type: application/json');

// Include the database configuration file
require '../../db_config.php';

// Check if 'year' is passed as a parameter
if (isset($_GET['year']) && is_numeric($_GET['year'])) {
    $year = intval($_GET['year']); // Convert the 'year' parameter to an integer
    
    // SQL query to get students based on the passed year in the grade table
    $sql = "SELECT s.student_id, s.student_name, s.father_name, s.user_id, s.grade_id, s.address, s.guardian, s.contact_number, s.registration_number, s.join_date, s.leave_date 
            FROM student s
            JOIN grade g ON s.grade_id = g.grade_id
            WHERE g.year = :year";
    
    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the year parameter
    $stmt->bindParam(':year', $year, PDO::PARAM_INT);
    $stmt->execute();
    
    // Fetch all the results
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($students)) {
        // Send response as JSON
        echo json_encode(array('status' => 'success', 'data' => $students));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'No students found for year ' . $year));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Year parameter is missing or invalid.'));
}

// Close connection (optional with PDO, since it's closed automatically when script ends)
?>
