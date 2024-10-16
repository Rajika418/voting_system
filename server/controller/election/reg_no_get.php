<?php
// Include external database configuration file
require '../../db_config.php';  // Make sure this path is correct

// Get the registration number from the request
if (isset($_GET['registration_number'])) {
    $registration_number = $_GET['registration_number'];

    // SQL query to retrieve the data
    $sql = "SELECT 
                student.student_id, 
                student.student_name, 
                student.grade_id, 
                grade.grade_name, 
                users.image 
            FROM student 
            INNER JOIN grade ON student.grade_id = grade.grade_id 
            INNER JOIN users ON student.user_id = users.user_id 
            WHERE student.registration_number = :registration_number";

    // Ensure $conn is available and connected before preparing the statement
    if ($conn) {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':registration_number', $registration_number, PDO::PARAM_STR);
        $stmt->execute();

        // Fetch the student data
        $student = $stmt->fetch();

        // If student found, return the data as JSON
        if ($student) {
            echo json_encode([
                'status' => 'success',
                'student_id' => $student['student_id'],
                'student_name' => $student['student_name'],
                'grade_id' => $student['grade_id'],
                'grade_name' => $student['grade_name'],
                'image' => $student['image']
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Student not found']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database connection error']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No registration number provided']);
}

?>
