<?php 
require "../../db_config.php";

// Check if the necessary POST data is provided
if (!isset($_POST['teacher_id'])) {
    echo json_encode(["success" => false, "message" => "Teacher ID is required"]);
    exit();
}

$teacher_id = $_POST['teacher_id'];
$user_name = isset($_POST['user_name']) ? $_POST['user_name'] : null;
$email = isset($_POST['email']) ? $_POST['email'] : null;
$teacher_name = isset($_POST['teacher_name']) ? $_POST['teacher_name'] : null;
$address = isset($_POST['address']) ? $_POST['address'] : null;
$contact_number = isset($_POST['contact_number']) ? $_POST['contact_number'] : null;
$nic = isset($_POST['nic']) ? $_POST['nic'] : null;
$join_date = isset($_POST['join_date']) ? $_POST['join_date'] : null;
$leave_date = isset($_POST['leave_date']) ? $_POST['leave_date'] : null;
$subject_id = isset($_POST['subject_id']) ? $_POST['subject_id'] : null;

// Handle the image upload
$image = null;
$image_dir = '../../../uploads/';
$base_url = 'http://localhost/voting_system/uploads/'; 

// Create the directory if it doesn't exist
if (!is_dir($image_dir)) {
    mkdir($image_dir, 0777, true);
}

try {
    // Begin transaction
    $conn->beginTransaction();

    // Retrieve current image and user_id from teacher_id
    $sql = "SELECT users.image AS user_image, users.user_id FROM teacher 
            JOIN users ON teacher.user_id = users.user_id 
            WHERE teacher.teacher_id = :teacher_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':teacher_id', $teacher_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        echo json_encode(["success" => false, "message" => "Teacher not found"]);
        exit();
    }

    $current_image = $result['user_image'];
    $user_id = $result['user_id'];

    // Handle image deletion and upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        if ($current_image) {
            $current_image_path = str_replace($base_url, $image_dir, $current_image);
            if (file_exists($current_image_path)) {
                unlink($current_image_path);
            }
        }

        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_name = basename($_FILES['image']['name']);
        $unique_image_name = uniqid() . '_' . $image_name;
        $image_path = $image_dir . $unique_image_name;

        if (move_uploaded_file($image_tmp_name, $image_path)) {
            $image = $base_url . $unique_image_name;
        } else {
            echo json_encode(["success" => false, "message" => "Image upload failed"]);
            exit();
        }
    } else {
        $image = $current_image;
    }

    // Update users table if any user fields are provided
    if ($user_name || $email || $image) {
        $sql = "UPDATE users SET 
                user_name = COALESCE(:user_name, user_name), 
                email = COALESCE(:email, email), 
                image = COALESCE(:image, image) 
                WHERE user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_name', $user_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
    }

    // Update teacher table if any teacher fields are provided
    if ($teacher_name || $address || $contact_number || $nic || $join_date || $leave_date) {
        $sql = "UPDATE teacher SET 
                teacher_name = COALESCE(:teacher_name, teacher_name), 
                address = COALESCE(:address, address), 
                contact_number = COALESCE(:contact_number, contact_number), 
                nic = COALESCE(:nic, nic), 
                join_date = COALESCE(:join_date, join_date), 
                leave_date = COALESCE(:leave_date, leave_date) 
                WHERE teacher_id = :teacher_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':teacher_name', $teacher_name);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':contact_number', $contact_number);
        $stmt->bindParam(':nic', $nic);
        $stmt->bindParam(':join_date', $join_date);
        $stmt->bindParam(':leave_date', $leave_date);
        $stmt->bindParam(':teacher_id', $teacher_id);
        $stmt->execute();
    }

    // Update subject_teacher table if subject_id is provided
    if ($subject_id) {
        // Remove old subject_teacher entries for this teacher
        $sql = "DELETE FROM subject_teacher WHERE teacher_id = :teacher_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':teacher_id', $teacher_id);
        $stmt->execute();

        // Insert new subject_teacher entry
        $sql = "INSERT INTO subject_teacher (subject_id, teacher_id) VALUES (:subject_id, :teacher_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':subject_id', $subject_id);
        $stmt->bindParam(':teacher_id', $teacher_id);
        $stmt->execute();
    }

    // Commit transaction
    $conn->commit();

    echo json_encode(["success" => true, "message" => "User and teacher records updated successfully"]);
} catch (Exception $e) {
    // Rollback transaction if something went wrong
    $conn->rollBack();
    
    echo json_encode(["success" => false, "message" => "Update failed: " . $e->getMessage()]);
}
?>
