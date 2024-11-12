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
    $required_fields = ['student_name', 'user_name', 'password', 'email', 'address', 'contact_number', 'registration_number', 'join_date'];
    $missing_fields = [];

    // Check for missing fields
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $missing_fields[] = $field;
        }
    }

    if (!empty($missing_fields)) {
        echo json_encode([
            "status" => "error",
            "message" => "Incomplete data",
            "missing_fields" => $missing_fields
        ]);
        exit();
    }

    // Retrieve form data
    $student_name = $_POST['student_name'];
    $user_name = $_POST['user_name'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password
    $email = $_POST['email'];
    $address = $_POST['address'];
    $contact_number = $_POST['contact_number'];
    $registration_number = $_POST['registration_number'];
    $join_date = $_POST['join_date'];
    $grade_id = $_POST['grade_id'] ?? null; // Optional grade ID

    // Determine whether to use father_name or guardian based on the checkbox
    $guardian = isset($_POST['guardian']) && !empty($_POST['guardian']) ? $_POST['guardian'] : null;
    $father_name = isset($_POST['father_name']) && !empty($_POST['father_name']) ? $_POST['father_name'] : null;

    // Ensure at least one of them is provided
    if (empty($guardian) && empty($father_name)) {
        echo json_encode([
            "status" => "error",
            "message" => "Either father name or guardian must be provided."
        ]);
        exit();
    }

    // Log received data
    $received_data = [
        'student_name' => $student_name,
        'user_name' => $user_name,
        'email' => $email,
        'address' => $address,
        'contact_number' => $contact_number,
        'registration_number' => $registration_number,
        'join_date' => $join_date,
        'grade_id' => $grade_id,
        'image' => isset($_FILES['image']) ? 'Provided' : 'Not provided',
        'parents_image' => isset($_FILES['parents_image']) ? 'Provided' : 'Not provided'
    ];

    error_log("Received data: " . json_encode($received_data));

    // Handle the image uploads
    $image = null;
    $parents_image = null;
    $image_dir = '../../../uploads/';
    $base_url = 'http://localhost/voting_system/uploads/';

    // Create the directory if it doesn't exist
    if (!is_dir($image_dir)) {
        mkdir($image_dir, 0777, true);
    }

    // Upload student image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_name = basename($_FILES['image']['name']);
        $unique_image_name = uniqid() . '_' . $image_name;
        $image_path = $image_dir . $unique_image_name;

        if (move_uploaded_file($image_tmp_name, $image_path)) {
            // Full URL with base URL included
            $image = $base_url . $unique_image_name;
        } else {
            echo json_encode(["message" => "Student image upload failed"]);
            exit();
        }
    } elseif (isset($_FILES['image']['error']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        echo json_encode(["message" => "Image upload error: " . $_FILES['image']['error']]);
        exit();
    }

    // Upload parents' image
    if (isset($_FILES['parents_image']) && $_FILES['parents_image']['error'] === UPLOAD_ERR_OK) {
        $parents_image_tmp_name = $_FILES['parents_image']['tmp_name'];
        $parents_image_name = basename($_FILES['parents_image']['name']);
        $unique_parents_image_name = uniqid() . '_' . $parents_image_name;
        $parents_image_path = $image_dir . $unique_parents_image_name;

        if (move_uploaded_file($parents_image_tmp_name, $parents_image_path)) {
            // Full URL with base URL included
            $parents_image = $base_url . $unique_parents_image_name;
        } else {
            echo json_encode(["message" => "Parents image upload failed"]);
            exit();
        }
    } elseif (isset($_FILES['parents_image']['error']) && $_FILES['parents_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        echo json_encode(["message" => "Parents image upload error: " . $_FILES['parents_image']['error']]);
        exit();
    }

    // Start database transaction
    $conn->beginTransaction();

    try {
        // Insert user data into the users table with the image URL
        $stmt_user = $conn->prepare("INSERT INTO users (user_name, password, email, role_id, image) VALUES (?, ?, ?, ?, ?)");
        $role_id = 3; // Fixed role ID for students
        $stmt_user->execute([$user_name, $password, $email, $role_id, $image]);

        // Get the last inserted user ID
        $user_id = $conn->lastInsertId();

        // Insert student data into the student table with parents_image URL
        $stmt_student = $conn->prepare("INSERT INTO student (student_name, user_id, grade_id, address, guardian, father_name, contact_number, registration_number, join_date, parents_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt_student->execute([$student_name, $user_id, $grade_id, $address, $guardian, $father_name, $contact_number, $registration_number, $join_date, $parents_image]);

        // Commit the transaction
        $conn->commit();
        header("Location: ../../../client/index.php?registration=success");
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollBack();
        echo json_encode([
            "status" => "error",
            "message" => "Student registration failed: " . $e->getMessage(),
            "received_data" => $received_data
        ]);
    }
}
?>
