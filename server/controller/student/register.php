<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Origin: *');

// Database connection
$host = "localhost";
$db_name = "voting_system";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(["message" => "Connection failed: " . $e->getMessage()]);
    exit();
}

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required_fields = ['student_name', 'user_name', 'password', 'email', 'address', 'contact_number', 'registration_number', 'guardian', 'join_date'];
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
    $guardian = $_POST['guardian'];
    $join_date = $_POST['join_date'];
    $grade_id = $_POST['grade_id'] ?? null; // Optional grade ID

    $received_data = [
        'student_name' => $student_name,
        'user_name' => $user_name,
        'email' => $email,
        'address' => $address,
        'contact_number' => $contact_number,
        'registration_number' => $registration_number,
        'guardian' => $guardian,
        'join_date' => $join_date,
        'grade_id' => $grade_id,
        'image' => isset($_FILES['image']) ? 'Provided' : 'Not provided'
    ];

    // Log received data
    error_log("Received data: " . json_encode($received_data));

    // Handle the image upload
    $image = null;
    $image_dir = $_SERVER['DOCUMENT_ROOT'] . '/voting_system/uploads/';

    if (!is_dir($image_dir)) {
        mkdir($image_dir, 0777, true); // Create the uploads directory if it doesn't exist
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_name = basename($_FILES['image']['name']);
        $image_path = $image_dir . $image_name;

        // Move the uploaded file to the desired directory
        if (move_uploaded_file($image_tmp_name, $image_path)) {
            $image = $image_name; // Only store the image name in the database, not the full path
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Image upload failed",
                "received_data" => $received_data
            ]);
            exit();
        }
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Image upload error or no image provided",
            "received_data" => $received_data
        ]);
        exit();
    }

    // Start database transaction
    $conn->beginTransaction();

    try {
        // Insert user data into the users table
        $stmt_user = $conn->prepare("INSERT INTO users (user_name, password, email, role_id, image) VALUES (?, ?, ?, ?, ?)");
        $role_id = 3; // Fixed role ID for students
        $stmt_user->execute([$user_name, $password, $email, $role_id, $image]);

        // Get the last inserted user ID
        $user_id = $conn->lastInsertId();

        // Insert student data into the student table
        $stmt_student = $conn->prepare("INSERT INTO student (student_name, user_id, grade_id, address, guardian, contact_number, registration_number, join_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt_student->execute([$student_name, $user_id, $grade_id, $address, $guardian, $contact_number, $registration_number, $join_date]);

        // Commit the transaction
        $conn->commit();

        echo json_encode([
            "status" => "success",
            "message" => "Student registration successful",
            "received_data" => $received_data
        ]);

        // Redirect to login page after success
        header("Location: ../../../client/login.html");
        exit();
        
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
