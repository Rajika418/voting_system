<?php 
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Origin: *');

// Include database configuration
require '../../db_config.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if student_id is provided
    if (empty($_POST['student_id'])) {
        echo json_encode([
            "status" => "error",
            "message" => "Missing student ID"
        ]);
        exit();
    }

    // Retrieve form data
    $student_id = $_POST['student_id'];
    $student_name = $_POST['student_name'] ?? null;
    $user_name = $_POST['user_name'] ?? null;
    $email = $_POST['email'] ?? null;
    $address = $_POST['address'] ?? null;
    $contact_number = $_POST['contact_number'] ?? null;
    $registration_number = $_POST['registration_number'] ?? null;
    $guardian = $_POST['guardian'] ?? null;
    $father_name = $_POST['father_name'] ?? null;
    $join_date = $_POST['join_date'] ?? null;
    $grade_id = $_POST['grade_id'] ?? null; // Optional grade ID
    $image = $_POST['image'] ?? null;

    // Start database transaction
    $conn->beginTransaction();

    try {
        // Update user data in the users table if any data is provided
        if ($user_name !== null || $email !== null || $image !== null) {
            $update_fields = [];
            $update_values = [];
            if ($user_name !== null) {
                $update_fields[] = "user_name = ?";
                $update_values[] = $user_name;
            }
            if ($email !== null) {
                $update_fields[] = "email = ?";
                $update_values[] = $email;
            }
            if ($image !== null) {
                $update_fields[] = "image = ?";
                $update_values[] = $image;
            }
            $update_values[] = $student_id;

            $sql_user_update = "UPDATE users SET " . implode(', ', $update_fields) . " WHERE user_id = (SELECT user_id FROM student WHERE student_id = ?)";
            $stmt_user = $conn->prepare($sql_user_update);
            $stmt_user->execute($update_values);
        }

        // Update student data in the student table if any data is provided
        if ($student_name !== null || $grade_id !== null || $address !== null || $guardian !== null || $contact_number !== null || $registration_number !== null || $join_date !== null) {
            $update_fields = [];
            $update_values = [];
            if ($student_name !== null) {
                $update_fields[] = "student_name = ?";
                $update_values[] = $student_name;
            }
            if ($grade_id !== null) {
                $update_fields[] = "grade_id = ?";
                $update_values[] = $grade_id;
            }
            if ($address !== null) {
                $update_fields[] = "address = ?";
                $update_values[] = $address;
            }
            if ($guardian !== null) {
                $update_fields[] = "guardian = ?";
                $update_values[] = $guardian;
            }
            if ($father_name !== null) {
                $update_fields[] = "father_name = ?";
                $update_values[] = $father_name;
            }
            if ($contact_number !== null) {
                $update_fields[] = "contact_number = ?";
                $update_values[] = $contact_number;
            }
            if ($registration_number !== null) {
                $update_fields[] = "registration_number = ?";
                $update_values[] = $registration_number;
            }
            if ($join_date !== null) {
                $update_fields[] = "join_date = ?";
                $update_values[] = $join_date;
            }
            $update_values[] = $student_id;

            $sql_student_update = "UPDATE student SET " . implode(', ', $update_fields) . " WHERE student_id = ?";
            $stmt_student = $conn->prepare($sql_student_update);
            $stmt_student->execute($update_values);
        }

        // Commit the transaction
        $conn->commit();

        echo json_encode([
            "status" => "success",
            "message" => "Student update successful"
        ]);
        
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollBack();
        echo json_encode([
            "status" => "error",
            "message" => "Student update failed: " . $e->getMessage()
        ]);
    }
}
?>
