<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Origin: *');

// Database connection
$host = 'localhost';
$dbname = 'voting_system';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if all required fields are present
    if (!isset($_POST['staff_name']) || empty($_POST['staff_name']) || !isset($_POST['email']) || empty($_POST['email'])) {
        echo json_encode(['status' => 'error', 'message' => 'staff_name and email are required']);
        exit();
    }

    // Handle the image upload
    $image = NULL;
    $image_dir = $_SERVER['DOCUMENT_ROOT'] . '/voting_system/uploads/';

    // Ensure the directory exists and create if not
    if (!is_dir($image_dir)) {
        mkdir($image_dir, 0777, true);
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_name = basename($_FILES['image']['name']);
        $image_path = $image_dir . $image_name;

        // Move the uploaded file to the desired directory
        if (move_uploaded_file($image_tmp_name, $image_path)) {
            $image = $image_path;
        } else {
            echo json_encode(["message" => "Image upload failed"]);
            exit();
        }
    }

    // Insert data into the staffs table
    $stmt = $conn->prepare("INSERT INTO staffs (staff_name, position, address, email, contact_number, image, join_date, leave_date) 
                            VALUES (:staff_name, :position, :address, :email, :contact_number, :image, :join_date, :leave_date)");
    
    $stmt->bindParam(':staff_name', $_POST['staff_name']);
    $stmt->bindParam(':position', $_POST['position']);
    $stmt->bindParam(':address', $_POST['address']);
    $stmt->bindParam(':email', $_POST['email']);
    $stmt->bindParam(':contact_number', $_POST['contact_number']);
    $stmt->bindParam(':image', $image);
    $stmt->bindParam(':join_date', $_POST['join_date']);
    $stmt->bindParam(':leave_date', $_POST['leave_date']);
    
    $stmt->execute();

    echo json_encode(['status' => 'success', 'message' => 'Staff added successfully']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}

// Close the connection
$conn = null;
?>
