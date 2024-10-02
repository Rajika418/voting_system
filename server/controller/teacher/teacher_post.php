<?php
require "../../db_config.php";

// Check if the necessary POST data is provided
if (!isset($_POST['teacher_name'], $_POST['address'], $_POST['contact_number'], $_POST['nic'], $_POST['user_name'], $_POST['password'], $_POST['email'])) {
    echo json_encode(["message" => "Incomplete data"]);
    exit();
}

// Set role_id to 2 (Teacher)
$role_id = 2;

// Hash the password
$hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Handle the image upload
$image = NULL;
$image_dir = '../../../uploads/'; // Corrected to a server file path
$base_url = 'http://localhost/voting_system/uploads/'; // Base URL for accessing images

// Create the directory if it doesn't exist
if (!is_dir($image_dir)) {
    mkdir($image_dir, 0777, true);
}

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_name = basename($_FILES['image']['name']);

    // Generate a unique name for the image to prevent overwriting
    $unique_image_name = uniqid() . '_' . $image_name;
    $image_path = $image_dir . $unique_image_name;

    // Move the uploaded file to the desired directory
    if (move_uploaded_file($image_tmp_name, $image_path)) {
        // Save the full URL for the image
        $image = $base_url . $unique_image_name;
    } else {
        echo json_encode(["message" => "Image upload failed"]);
        exit();
    }
} else {
    // No image uploaded or error in upload
    if (isset($_FILES['image']['error']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        echo json_encode(["message" => "Image upload error: " . $_FILES['image']['error']]);
        exit();
    }
}

$join_date = isset($_POST['join_date']) ? $_POST['join_date'] : NULL;
$leave_date = isset($_POST['leave_date']) ? $_POST['leave_date'] : NULL;

// Get selected subject
$subject_id = isset($_POST['subject_id']) ? $_POST['subject_id'] : NULL;

try {
    // Begin transaction
    $conn->beginTransaction();

    // Insert into users table with role_id set to 2 (Teacher)
    $sql = "INSERT INTO users (user_name, password, email, role_id, image) VALUES (:user_name, :password, :email, :role_id, :image)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_name', $_POST['user_name']);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':email', $_POST['email']);
    $stmt->bindParam(':role_id', $role_id);
    $stmt->bindParam(':image', $image);
    $stmt->execute();
    $user_id = $conn->lastInsertId();

    // Retrieve the newly created user record
    $sql = "SELECT user_id, user_name, email, role_id, image FROM users WHERE user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $user_record = $stmt->fetch(PDO::FETCH_ASSOC);

    // Insert into teacher table
    $sql = "INSERT INTO teacher (teacher_name, address, contact_number, nic, user_id, join_date, leave_date) 
            VALUES (:teacher_name, :address, :contact_number, :nic, :user_id, :join_date, :leave_date)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':teacher_name', $_POST['teacher_name']);
    $stmt->bindParam(':address', $_POST['address']);
    $stmt->bindParam(':contact_number', $_POST['contact_number']);
    $stmt->bindParam(':nic', $_POST['nic']);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':join_date', $join_date);
    $stmt->bindParam(':leave_date', $leave_date);
    $stmt->execute();
    $teacher_id = $conn->lastInsertId();

    // Retrieve the newly created teacher record
    $sql = "SELECT teacher_id, teacher_name, address, contact_number, nic, user_id, join_date, leave_date 
            FROM teacher WHERE teacher_id = :teacher_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':teacher_id', $teacher_id);
    $stmt->execute();
    $teacher_record = $stmt->fetch(PDO::FETCH_ASSOC);

    // Insert into subject_teacher table if subject_id is provided
    if ($subject_id) {
        $sql = "INSERT INTO subject_teacher (subject_id, teacher_id) VALUES (:subject_id, :teacher_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':subject_id', $subject_id);
        $stmt->bindParam(':teacher_id', $teacher_id);
        $stmt->execute();
    }

    // Commit transaction
    $conn->commit();
    // Redirect to login page with success parameter
    header("Location: ../../../client/login.html?registration=success");
    exit();
} catch (Exception $e) {
    // Rollback transaction if something went wrong
    $conn->rollBack();

    // Encode error message and redirect back to registration page
    $error_message = urlencode("Registration failed: " . $e->getMessage());
}
